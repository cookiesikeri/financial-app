<?php

namespace App\Http\Controllers\Apis;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Apis\UtilityController;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AirtimeController extends Controller
{
    public $utility;

    public function __construct(UtilityController $utility)
    {
        $this->utility = $utility;
    }

    public function request(Request $request)
    {

        // return response()->json([], 200);
        $data = array(
            'phone'       => $request->phone,
            'email'       => $request->email,
            'amount'      => $request->amount,
            'service_id'  => $request->service_id,
            'amount_paid' => $request->amount_paid,
            'transaction_pin' => $request->transaction_pin,
        );

        $validator = Validator::make($data, [
            'phone'       => 'required|digits:11',
            'email'       => 'required|email',
            'amount'      => 'required|numeric|gt:0',
            'service_id'  => 'required|numeric',     //Rule::in(['MTN', 'Airtel', 'Glo', '9mobile']),
            'transaction_pin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $data['transaction_id'] = $this->utility->generateTransactionID(1);
        $data['status']         = 0;
        $data['commission']     = 0;
        $data['payment_method'] = 'WALLET';
        $data['platform']       = 'MOBILE';
        $data['user_id']        = $request->gLocatorID;
        $data['payment_ref']       = 'NILL';

        $resp = array(
            'msg'   =>  'Pending...',
            'tNo'   =>  'starting',
            'success' => false
        );


        // $todaysPendingTransactions = $this->utility->getTodaysPendingTransactionsCount($data['user_id'], $data['service_id']);
        // if($todaysPendingTransactions === "s404") {
        //     $resp['msg'] = 'Unknown service! Please select a valid service to continue.';
        //     $resp['tNo'] = 'error';
        // } else if($todaysPendingTransactions === "st404") {
        //     $resp['msg'] = 'Unknown service! The service you selected could not be resolved!';
        //     $resp['tNo'] = 'error';
        // } else if($todaysPendingTransactions > 0) {
        //     $resp['msg'] = 'You have 1 or more previously pending transactions, will you like to resume them?';
        //     $resp['tNo'] = 'pending';
        // } else if($todaysPendingTransactions <= 0) {}
        // also we need to check if user exists using the gLocatorID.
        // due to database design, user must already exist in the users table other wise, integrity constraint for field user_id will fail.
        //$userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
        $userID = $this->utility->getUserByID(Auth::id());

        if (!empty($userID) && $userID !== -1) {
            // this user already has an account with us.
            $data['user_id'] = $userID;
        } else {
            return response()->json(['message'=>'Unauthenticated user.'], 401);
            // this user does not exist so create an account for the user;
            /* $userData = array(
                'name'     =>  'Full name',
                // 'lname'     =>  'Last name',
                'phone'     =>  $data['phone'],
                'email'     =>  $data['email'],
                'password'  =>  $this->utility->generatePassword(),
                'gLocatorID'    =>  $data['user_id'],
                'role_id'       =>  1
            );
            $user = $this->utility->createUser($userData);
            $data['user_id'] = $user->id; */
        }
        $airtimePurchase = \App\Models\AirtimeTransaction::on('mysql::write')->create($data);

        $user = \App\Models\User::on('mysql::read')->where('email', $airtimePurchase->email)->first();
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
        if($current_balance > floatval($airtimePurchase->amount)){
            $new_balance = floatval($current_balance) - floatval($airtimePurchase->amount);
            $wallet = Wallet::on('mysql::write')->where('user_id',$user->id)->first();
            $wallet->update(['balance' => $new_balance]);
//            $user->wallet()->update(['balance' => $new_balance]);
            $serviceName = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($airtimePurchase->service_id);
            $description = $serviceName . ' N ' . intval($airtimePurchase->amount) . ' to ' . $airtimePurchase->phone;
            //app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $airtimePurchase->amount, $new_balance, 2, $description, 1, $airtimePurchase->transaction_id);
            WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$user->wallet->id,
                'type'=>'Debit',
                'amount'=>$airtimePurchase->amount,
                'description'=>'Airtime Purchase',
                'bank_name'=>'Transave',
                'transfer'=>false,
                'transaction_type'=>'wallet',
                'status'=>'success',
            ]);
            $airtimePurchase->update(['amount_paid' => $airtimePurchase->amount]);
            // fire event to dispense airtime
            event(new \App\Events\AirtimeRequestEvent($airtimePurchase));
            $resp['msg'] = 'You should receive your airtime shortly with a notification to your e-mail and phone number.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
            $resp['success'] = true;
        }else{
            $resp['msg'] = 'Insufficient funds. Please TopUp your wallet.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
        }

        /*// after committing the transaction to the database, now lets try to verify whether the payment was actually successful before vending.
        if (env('PAYMENT_MODE') == 1) {
            $verifyPayment = $this->utility->verifyPayment($data['payment_ref'], 'airtime', $airtimePurchase->id, env('MODE'));
        } else {
            $verifyPayment = $this->utility->verifyRavePayment($data['payment_ref'], 'airtime', $airtimePurchase->id, env('MODE'));
        }
        if ($verifyPayment == -1) {
            $resp['msg'] = 'We were unable to initiate the process of verifying your payment status. Please contact our customer support lines with your transaction reference for help.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
        } else if ((1 <= $verifyPayment) && ($verifyPayment <= 88)) {
            $resp['msg'] = 'Unfortunately, our servers encountered an error trying to validate your payment status. Please contact our customer support lines with your transaction reference for help.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
        } else if ($verifyPayment == 404) {
            $resp['msg'] = 'We could not find your payment transaction reference. Your payment might have been declined. Please contact our customer support lines with your transaction reference for help.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
        } else if ($verifyPayment == '503') {
            $resp['msg'] = 'Unable to verify transaction. Please contact our customer support lines with your transaction reference for help.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
        } else if ($verifyPayment == 100) {
            // fire event to dispense airtime
            event(new \App\Events\AirtimeRequestEvent($airtimePurchase));
            $resp['msg'] = 'You should receive your airtime shortly with a notification to your e-mail and phone number.';
            $resp['tNo'] = $airtimePurchase->transaction_id;
            $resp['success'] = true;
        } else {
            $resp['msg'] = $verifyPayment;
            $resp['tNo'] = $airtimePurchase->transaction_id;
        } */
        return response()->json($resp);
    }

    public function pendingTransaction($phone, $userID)
    {
        $today = date("Y-m-d");
        $pending = \App\Models\AirtimeTransaction::on('mysql::read')->where('status', '<>', 2)
            ->where('user_id', $userID)
            ->where('date_created', 'LIKE', '%' . $today . '%')
            ->Orwhere('date_modified', 'LIKE', '%' . $today . '%')
            ->get();

        dd($pending);
    }
}
