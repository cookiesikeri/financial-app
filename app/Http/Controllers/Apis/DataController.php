<?php

namespace App\Http\Controllers\Apis;

use App\Models\DataBundle;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Apis\UtilityController;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public $utility;
    public function __construct(UtilityController $utility) {
        $this->utility = $utility;
    }

    public function getBundles($networkID) {
        $bundles = DataBundle::on('mysql::read')->where('service_id', $networkID)->get();
        if($bundles->count() > 0) {
            return response()->json($bundles);
        }
        return response()->json('404');
    }

    public function request(Request $request) {
        $data = array(
            'service_id'        =>  $request->service_id,
            'data_bundles_id'   =>  $request->data_bundles_id,
            'email'             =>  $request->email,
            'phone'             =>  $request->phone,
            'amount'            =>  $request->amount,
            'amount_paid'       =>  $request->amount_paid,
            'transaction_pin'=>$request->transaction_pin,
        );

        $validator = Validator::make($data, [
            'service_id'        =>  'required|numeric',
            'data_bundles_id'   =>  'required|numeric',
            'email'             =>  'required|email',
            'phone'             =>  'required|digits:11',
            'amount'            =>  'required|numeric|gt:0',
            'amount_paid'       =>  'required|numeric',
            'transaction_pin' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        $data['transaction_id'] = $this->utility->generateTransactionID(2);
        $data['status'] = 0;
        $data['commission'] = 0;
        $data['payment_method'] = 'WALLET';
        $data['platform'] = 'MOBILE';
        $data['payment_ref'] = 'NILL';
        $data['user_id'] = $request->gLocatorID;

        $resp = array(
            'msg'   =>  'Pending...',
            'tNo'   =>  'starting',
            'success' => false,
        );

        $todaysPendingTransactions = $this->utility->getTodaysPendingTransactionsCount($data['user_id'], $data['service_id']);

        if($todaysPendingTransactions === "s404") {
            $resp['msg'] = 'Unknown service! Please select a valid service to continue.';
            $resp['tNo'] = 'error';
        } else if($todaysPendingTransactions === "st404") {
            $resp['msg'] = 'Unknown service! The service you selected could not be resolved!';
            $resp['tNo'] = 'error';
        } else if($todaysPendingTransactions > 0) {
            $resp['msg'] = 'You have 1 or more previously pending transactions, will you like to resume them?';
            $resp['tNo'] = 'pending';
        } else if($todaysPendingTransactions <= 0) {
            //$userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
            $userID = $this->utility->getUserByID(Auth::id());
            if(!empty($userID) && $userID !== -1) {
                $data['user_id'] = $userID;
            } else {
                return response()->json(['message'=>'Unauthenticated user.'], 422);
                /* $userData = array(
                    'name'     =>  'Full name',
                    'phone'     =>  $data['phone'],
                    'email'     =>  $data['email'],
                    'password'  =>  $this->utility->generatePassword(),
                    'gLocatorID'    =>  $data['user_id'],
                    'role_id'       =>  1
                );
                $user = $this->utility->createUser($userData);
                $data['user_id'] = $user->id; */
            }
            $dataPurchase = \App\Models\DataTransaction::on('mysql::write')->create($data);

            $user = \App\Models\User::on('mysql::read')->where('email', $dataPurchase->email)->first();

            if(!$user){
                return response()->json(['message'=>'User not found.'], 404);
            }

            if(empty($user->transaction_pin)){
                return response()->json(['message'=>'Transaction Pin not set.'], 422);
            }

            if(!Hash::check($data['transaction_pin'], $user->transaction_pin))
            {
                return response()->json(['message'=>'Incorrect Pin!'], 404);
            }
            $current_balance = floatval($user->wallet->balance);
            if($current_balance > floatval($dataPurchase->amount)){
                $new_balance = $current_balance - intval($dataPurchase->amount);
                $wallet = Wallet::on('mysql::write')->where('user_id',$user->id)->first();
                $wallet->update(['balance' => $new_balance]);
//                $user->wallet()->update(['balance' => $new_balance]);
                $serviceName = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($dataPurchase->service_id);
                $description = $serviceName . ' N ' . $dataPurchase->amount . ' to ' . $dataPurchase->phone;
                //app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $dataPurchase->amount, $new_balance, 2, $description, 1, $dataPurchase->transaction_id);
                WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=>$user->wallet->id,
                    'type'=>'Debit',
                    'amount'=>$dataPurchase->amount,
                    'status'=>'success',
                    'description'=>'Data purchase',
                ]);
                $dataPurchase->update(['amount_paid' => $dataPurchase->amount]);

                event(new \App\Events\DataRequestEvent($dataPurchase));
                $resp['msg'] = 'You should receive your data bundle shortly with a notification to your e-mail and phone number.';
                $resp['tNo'] = $dataPurchase->transaction_id;
                $resp['success'] = true;
            }else{
                WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=>$user->wallet->id,
                    'type'=>'Debit',
                    'amount'=>$dataPurchase->amount,
                    'status'=>'failed',
                    'description'=>'Data purchase',
                ]);
                $resp['msg'] = 'Insufficient funds. Please TopUp your wallet.';
                $resp['tNo'] = $dataPurchase->transaction_id;
            }

            /* if(env('PAYMENT_MODE') == 1) {
                $verifyPayment = $this->utility->verifyPayment($data['payment_ref'], 'data', $dataPurchase->id, env('MODE'));
            } else {
                $verifyPayment = $this->utility->verifyRavePayment($data['payment_ref'], 'data', $dataPurchase->id, env('MODE'));
            }

            if($verifyPayment == -1) {
                $resp['msg'] = 'We were unable to initiate the process of verifying your payment status. Please contact our customer support lines with your transaction reference for help.';
                $resp['tNo'] = $dataPurchase->transaction_id;
            } else if((1 <= $verifyPayment) && ($verifyPayment <= 88)) {
                $resp['msg'] = 'Unfortunately, our servers encountered an error trying to validate your payment status. Please contact our customer support lines with your transaction reference for help.';
                $resp['tNo'] = $dataPurchase->transaction_id;
            } else if($verifyPayment == 404) {
                $resp['msg'] = 'We could not find your payment transaction reference. Your payment might have been declined. Please contact our customer support lines with your transaction reference for help.';
                $resp['tNo'] = $dataPurchase->transaction_id;
            } else if($verifyPayment == '503') {
                $resp['msg'] = 'Unable to verify transaction. Please contact our customer support lines with your transaction reference for help.';
                $resp['tNo'] = $dataPurchase->transaction_id;
            } else if($verifyPayment == 100) {
                event(new \App\Events\DataRequestEvent($dataPurchase));
                $resp['msg'] = 'You should receive your data bundle shortly with a notification to your e-mail and phone number.';
                $resp['tNo'] = $dataPurchase->transaction_id;
                $resp['success'] = true;
            } else {
                $resp['msg'] = $verifyPayment;
                $resp['tNo'] = $dataPurchase->transaction_id;
            } */
        }

        return response()->json($resp);
    }
}
