<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Apis\UtilityController;
use App\Models\AccountNumber;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TVController extends Controller
{
    protected $jwt;
    public function __construct(UtilityController $utility, JWTAuth $jwt) {
        $this->utility = $utility;
        $this->jwt = $jwt;
    }

    public function getTVInfo($providerID) {
        $bundles = \App\Models\TVBundle::on('mysql::read')->where('service_id', $providerID)->get();

        if(count($bundles) <= 0) {
            return response()->json('404');
        }

        return response()->json($bundles);
    }

    public function getCardInfo(Request $request) {
        try{
            $serviceCode    = 0;
            $tvAmount = 0;
            $respFormat     = 'JSON';
            $package        = $request->package;
            $packageID      = $request->packageID;
            $provider       = $request->provider;

            $resp = array(
                'status'    =>  0,
                'msg'       =>  'Pending',
                'success'   =>  false,
            );

            $data = array(
                'service_id'    =>  $request->service_id,
                'smartcard_num' =>  $request->smartcard_num,
                'amount'        =>  $request->amount,
                'email'         =>  $request->email,
                'phone'         =>  $request->phone,
                'user_id'       =>  $request->gLocatorID,
            );

            $validator = Validator::make($data, [
                'service_id'    =>  'required|numeric',
                'smartcard_num' =>  'required|numeric',
                'amount'        =>  'required|numeric|gt:0',
                'email'         =>  'required|email',
                'phone'         =>  'required|digits:11'
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors());
            }

            $service = \App\Models\Service::on('mysql::read')->find($data['service_id']);
            if($service) {

                if($service->name == 'StarTimes' && 23 == $service->id) {
                    // 17 is for startimes best is to get it rather than hardcode it.
                    $bundleID = \App\Models\TVBundle::on('mysql::read')->findOrFail($packageID);
                    $data['tv_bundles_id'] = $bundleID->id;
                    $serviceCode = 'StarTimes';
                    $tvAmount = $bundleID->amount;
                } else {
                    $data['tv_bundles_id'] = $packageID;
                    $bundleID = \App\Models\TVBundle::on('mysql::read')->findOrFail($packageID);
                    $serviceCode = $bundleID->code;
                }

                $transactionID = $this->utility->generateTransactionID(4);
                $data['transaction_id'] = $transactionID;
                $data['status'] = 0;
                $data['amount_paid'] = 0.00;

                $data['commission'] = $service->commission;
                $data['payment_method'] = '';
                $data['payment_ref'] = 'Pending';
                $data['platform'] = 'MOBILE';
                $data['bundle_name'] = $package;
                $data['transaction_trials'] = 1;

                //$userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
                $userID = $this->utility->getUserByID(Auth::id());
                if(!empty($userID) && $userID !== -1) {
                    $data['user_id'] = $userID;
                } else {
                    return response()->json(['message'=>'User not Authenticated!!', 'status'=>404], 404);
                    /* $userData = array(
                        'name'     =>  'First name',
                        'phone'     =>  $data['phone'],
                        'email'     =>  $data['email'],
                        'password'  =>  $this->utility->generatePassword(),
                        'gLocatorID'    =>  (string)$data['user_id'],
                        'role_id'       =>  1
                    );
                    $user = $this->utility->createUser($userData);
                    $data['user_id'] = $user->id; */
                }

                $apiString = $this->utility->generateTVAPIString(substr($transactionID, -12), $provider, $data['smartcard_num'], $serviceCode, env('MODE'));

                $hash = $this->utility->hashAPIString($apiString, env('MODE'));

                $cardInfo = $this->utility->getTVCardInfo(substr($transactionID, -12), $data['smartcard_num'], $serviceCode, $provider, $hash, $respFormat, env('MODE'), $tvAmount);

                if($cardInfo['status'] === 1) {
                    $customerData = $cardInfo['msg'];
                    $data['access_token'] = $customerData['access_token'];
                    $data['customer_name'] = $customerData['customer'];

                    // save this transaction.
                    $transaction = \App\Models\TVTransaction::on('mysql::write')->create($data);
                    $amountToPay = intval($data['amount']) + intval($service->service_charge);
                    $resp['status'] = 1;
                    $resp['success'] = true;
                    $resp['msg'] = array('customerName' => $customerData['customer'], 'customerNumber' => $customerData['customer_number'], 'transactionID' => $transactionID, 'amountToPay' => $amountToPay, 'token' => $customerData['access_token']);
                }else{
                    $resp['status'] = -1;
                    $resp['msg'] = $cardInfo;
                }
            } else {
                $resp['status'] = -1;
                $resp['msg'] = 'Service not found.';
            }

            return response()->json($resp);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'TV Controller - Get smartcard info method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 420);
        }
    }

    // public function getCardInfo(Request $request) {
    //     $serviceCode    = 0;
    //     $respFormat     = 'JSON';
    //     $package        = $request->package;
    //     $packageID      = $request->providerID;
    //     $provider       = $request->provider;

    //     $resp = array(
    //         'status'    =>  0,
    //         'msg'       =>  'Pending',
    //         'success'   =>  false,
    //     );

    //     $data = array(
    //         'service_id'    =>  $request->providerID,
    //         'smartcard_num' =>  $request->card,
    //         'amount'        =>  $request->amount,
    //         'email'         =>  $request->email,
    //         'phone'         =>  $request->phone,
    //         'user_id'       =>  $request->gLocatorID,
    //     );

    //     $validator = \Validator::make($data, [
    //         'service_id'    =>  'required|numeric',
    //         'smartcard_num' =>  'required|numeric',
    //         'amount'        =>  'required|numeric',
    //         'email'         =>  'required|email',
    //         'phone'         =>  'required|digits:11'
    //     ]);

    //     if($validator->fails()) {
    //         return response()->json($validator->errors());
    //     }

    //     $service = \App\Models\Service::find($data['service_id']);
    //     if($service->count() > 0) {
    //         if($packageID == $data['amount']) {
    //             // 17 is for startimes best is to get it rather than hardcode it.
    //             // $bundleID = \App\Models\TVBundle::where('code', 'StarTimes')->first();
    //             $bundleID = \App\Models\TVBundle::where('service_id',$packageID)->first();
    //             $data['tv_bundles_id'] = $bundleID->id;
    //             $serviceCode = 'StarTimes';
    //         } else {
    //             $data['tv_bundles_id'] = $packageID;
    //             $bundleID = \App\Models\TVBundle::find($packageID);
    //             $serviceCode = $bundleID->code;
    //         }

    //         $transactionID = $this->utility->generateTransactionID(4);
    //         $data['transaction_id'] = $transactionID;
    //         $data['status'] = 0;
    //         $data['amount_paid'] = 0.00;

    //         $data['commission'] = $service->commission;
    //         $data['payment_method'] = '';
    //         $data['payment_ref'] = 'Pending';
    //         $data['platform'] = 'MOBILE';
    //         $data['bundle_name'] = $package;
    //         $data['transaction_trials'] = 1;

    //         $userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
    //         if($userID > 0) {
    //             $data['user_id'] = $userID;
    //         } else {
    //             $userData = array(
    //                 'name'     =>  'Full Name',
    //                 // 'lname'     =>  'Last name',
    //                 'phone'     =>  $data['phone'],
    //                 'email'     =>  $data['email'],
    //                 'password'  =>  $this->utility->generatePassword(),
    //                 'gLocatorID'    =>  (string)$data['user_id'],
    //                 'role_id'       =>  1
    //             );
    //             $user = $this->utility->createUser($userData);
    //             $data['user_id'] = $user->id;
    //         }

    //         $apiString = $this->utility->generateTVAPIString(substr($transactionID, -12), $provider, $data['smartcard_num'], $serviceCode, env('MODE'));

    //         $hash = $this->utility->hashAPIString($apiString, env('MODE'));

    //         $cardInfo = $this->utility->getTVCardInfo(substr($transactionID, -12), $data['smartcard_num'], $serviceCode, $request->provider, $hash, $respFormat, env('MODE'));

    //         // return response()->json($cardInfo);

    //         if($cardInfo['status'] === 1) {

    //             $customerData = $cardInfo['msg'];
    //             $data['access_token'] = $customerData['access_token'];
    //             $data['customer_name'] = $customerData['customer'];

    //             // save this transaction.
    //             $transaction = \App\Models\TVTransaction::on('mysql::write')->create($data);
    //             $amountToPay = intval($data['amount']) + intval($service->service_charge);
    //             $resp['status'] = 1;
    //             $resp['success'] = true;
    //             $resp['msg'] = array('customerName' => $customerData['customer'], 'customerNumber' => $customerData['customer_number'], 'transactionID' => $transactionID, 'amountToPay' => $amountToPay, 'token' => $customerData['access_token']);
    //         }
    //     } else {
    //         $resp['status'] = -1;
    //         $resp['msg'] = 'Service not found.';
    //     }

    //     return response()->json($resp);
    // }

    public function request(Request $request) {
        $resp = array(
            'status'    =>  0,
            'msg'       =>  'Pending',
            'success'   => false
        );
        try{
            $request->validate([
                'transaction_pin'=>'required',
                'user_id'=>'required|uuid',
                'token'=>'required',
                'amount_paid'=>'required',
            ]);
        }catch(ValidationException $e){
            return response()->json(['message'=>$e->getMessage(), 'errors'=>$e->errors()], 420);
        }

        $locatorID          = Auth::id();
        $amountPaid         = $request->input('amount_paid');
        $token              = $request->input('token');
        $pin = $request->input('transaction_pin');


        $tvTransaction = \App\Models\TVTransaction::on('mysql::write')->where('access_token', $token)->first();

        if(!$tvTransaction) {
            $resp['status'] = -1000;
            $resp['msg'] = 'TV Transaction not found.';

            return response()->json($resp);
        }

        $rUserID = $this->utility->getUserByGLocatorID($locatorID, $tvTransaction->email);

        $user = \App\Models\User::on('mysql::read')->find($tvTransaction->user_id);
        if(!$user){
            return response()->json(['message'=>'User not found.'], 404);
        }
        if(empty($user->transaction_pin)){
            return response()->json(['message'=>'Transaction Pin not set.'], 422);
        }

        if(!Hash::check($pin, $user->transaction_pin))
        {
            return response()->json(['message'=>'Incorrect Pin!'], 404);
        }

        $tvProvider = \App\Models\Service::on('mysql::read')->find($tvTransaction->service_id);
        $tvBundle = \App\Models\TVBundle::on('mysql::read')->find($tvTransaction->tv_bundles_id);
        $service = \App\Models\Service::on('mysql::read')->find($tvTransaction->service_id);

        $wallet = Wallet::on('mysql::write')->where('user_id', $user->id)->first();
        $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
        $current_balance = intval($wallet->balance);
        if($current_balance > (intval($tvTransaction->amount) + intval($service->service_charge))){

            $description = $tvBundle->name . ' N ' . $tvTransaction->amount . ' to ' . $tvTransaction->smartcard_num;
            //app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $tvTransaction->amount, $new_balance, 2, $description, 1, $tvTransaction->transaction_id);
            WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$user->wallet->id,
                'type'=>'Debit',
                'amount'=>$tvTransaction->amount,
                'description'=>'TV Bill Payment',
                /* 'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$user->name, */
                'transfer'=>false,
                'reference'=>$tvTransaction->transaction_id,
                'status'=>'success',
            ]);
            $tvTransaction->update(['amount_paid' => ($tvBundle->amount + $service->service_charge)]);

            $tvTransaction->update([
                'status'            =>  1,
                'amount_paid'       =>  $amountPaid,
                'payment_method'    =>  'WALLET',
            ]);

            event(new \App\Events\NewTVVendEvent($tvTransaction));
            $updatedTransaction = \App\Models\TVTransaction::on('mysql::read')->where('access_token', $token)->first();
            if($updatedTransaction->status != 2) {
                // something must have gone wrong while trying to dispense.
                $resp['status'] = -2000;
                $resp['msg'] = 'Failed.';
            } else {
                $new_balance = $current_balance - intval($tvBundle->amount + $service->service_charge);
                $wallet->update(['balance' => $new_balance]);
                $resp['status'] = 2000;
                $resp['success'] = true;
                $resp['msg'] = 'success';
            }
        }else{
            $resp['status'] = -1;
            $resp['msg'] = 'Insufficient funds. Please TopUp your wallet.';
            WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$user->wallet->id,
                'type'=>'Debit',
                'amount'=>$tvTransaction->amount,
                'description'=>'TV Bill Payment',
                /* 'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$user->name, */
                'transfer'=>false,
                'reference'=>$tvTransaction->transaction_id,
                'status'=>'failed',
            ]);
        }
        /* if(env('PAYMENT_MODE') == 1) {
            $verifyPayment = $this->utility->verifyPayment($paymentReference, 'tv', $tvTransaction->id, env('MODE'));
        } else {
            $verifyPayment = $this->utility->verifyRavePayment($paymentReference, 'tv', $tvTransaction->id, env('MODE'));
        }

        if($verifyPayment == -1) {
            $resp['status'] = $verifyPayment;
            $resp['msg'] = 'We were unable to initiate the process of verifying your payment status. Please contact our customer support lines with your transaction reference for help.';
        } else if((1 <= $verifyPayment) && ($verifyPayment <= 88)) {
            $resp['status'] = $verifyPayment;
            $resp['msg'] = 'Unfortunately, our servers encountered an error trying to validate your payment status. Please contact our customer support lines with your transaction reference for help.';
        } else if($verifyPayment == 404) {
            $resp['status'] = $verifyPayment;
            $resp['msg'] = 'We could not find your payment transaction reference. Your payment might have been declined. Please contact our customer support lines with your transaction reference for help.';
        } else if($verifyPayment == '503') {
            $resp['status'] = $verifyPayment;
            $resp['msg'] = 'Unable to verify transaction. Please contact our customer support lines with your transaction reference for help.';
        } else if($verifyPayment == 100) {
            if(!$tvTransaction) {
                $resp['status'] = -1000;
                $resp['msg'] = '404';
            } else {
                $tvTransaction->update([
                    'status'            =>  1,
                    'payment_ref'       => $paymentReference,
                    'amount_paid'       =>  $amountPaid,
                    'payment_method'    =>  'PAYSTACK',
                ]);

                event(new \App\Events\NewTVVendEvent($tvTransaction));
                $updatedTransaction = \App\Models\TVTransaction::where('access_token', $token)->first();
                if($updatedTransaction->status != 2) {
                    // something must have gone wrong while trying to dispense.
                    $resp['status'] = -2000;
                    $resp['msg'] = 'Failed.';
                } else {
                    $resp['status'] = 2000;
                    $resp['success'] = true;
                    $resp['msg'] = 'success';
                }
            }
        } else {
            $resp['status'] = -1;
            $resp['msg'] = $verifyPayment;
        } */

        return response()->json($resp);
    }
}
