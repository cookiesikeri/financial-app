<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Apis\UtilityController;
use App\Models\AccountNumber;
use App\Models\FailedEmail;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PowerController extends Controller
{
    protected $jwt;
    public function __construct(UtilityController $utility, JWTAuth $jwt) {
        $this->utility = $utility;
        $this->jwt = $jwt;
    }

    public function getMeterInfo(Request $request) {
        try{
            $resp = array(
                'status'          => 0,
                'msg'             => 'Pending',
                'token'           => 0,
                'serviceProvider' => 0,
                'amount'          => 0,
            );
            $data = array(
                'stateID'   => $request->stateID,
                'meterType' => $request->meterType,
                'meter_num' => $request->meter_num,
                'amount'    => $request->amount,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'user_id'   => $request->gLocatorID,
                'state' => $request->state,
                'disco' => $request->disco,
            );

            $validator = Validator::make($data, [
                'stateID'       =>  'required|numeric',
                'meterType'     =>  'required|numeric',
                'meter_num'     =>  'required|numeric',
                'amount'        =>  'required|numeric|gt:0',
                'email'         =>  'required|email',
                'phone'         =>  'required|numeric',
                'state' => 'required|string',
                'disco' => 'required|numeric',
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors());
            }

            $referenceID = $this->utility->generateTransactionID(3);

            $state = strtolower($request->state);

            $disco = $this->utility->getDiscoProvider($state, $request->disco, $data['meterType']);
            $serviceName = str_replace('_', ' ', $disco);
            $serviceInfo = $this->utility->getPowerServiceInfo($serviceName);

            if(!is_array($serviceInfo) && $serviceInfo == "404") {
                $resp['status'] = -500;
                $resp['msg'] = "Selected provider not available on our platform!";
            } else {
                if($serviceInfo instanceof Service && $data['amount'] < $serviceInfo->minimum_value) {
                    $resp['status'] = -3;
                    $resp['msg']    = 'Minimum required amount is ₦'.$serviceInfo->minimum_value;
                } else if($serviceInfo instanceof Service && $data['amount'] > $serviceInfo->maximum_value) {
                    $resp['status'] = 30000;
                    $resp['msg']    = 'Maximum required amount is ₦'.$serviceInfo->maximum_value;
                } else {
                    $referenceID = $this->utility->generateTransactionID(3);
                    $apiString = $this->utility->generatePowerAPIString($data['meter_num'], substr($referenceID, -12), $disco, env('MODE'));

                    $hashString = $this->utility->hashAPIString($apiString, env('MODE'));

                    $meterInfo = $this->utility->getMeterInfo($data['meter_num'], substr($referenceID, -12), $disco, $hashString, env('MODE'));
                    // remember to catch error 12 = unknown user
                    if($meterInfo['status'] == '12') {
                        $resp['status'] = 12;
                        $resp['msg']    = 'Unknown';
                    } else if(is_array($meterInfo['msg'])) {
                        // user meter verification successful. Log the transaction to the database.
                        $customerData = $meterInfo['msg'];

                        $data['transaction_id'] = $referenceID;
                        $data['customer_name']  = $customerData['name'];
                        $data['customer_address']= $customerData['address'];
                        $data['token']          = "PENDING";
                        $data['access_token']   = $meterInfo['status'];
                        $data['status']         = 0;
                        $data['amount_paid']    = 0.00;
                        $data['commission']     = $serviceInfo->commission;
                        $data['payment_method'] = 'WALLET';
                        $data['payment_ref']    = 'Pending';
                        $data['platform']       = 'MOBILE';
                        $data['units']          = '0';
                        $data['service_id']     = $serviceInfo->id;
                            //$userID           = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
                            $userID           = $this->utility->getUserByID(Auth::id());
                        if(!empty($userID) && $userID !== -1) {
                            $data['user_id'] = $userID;
                        } else {
                            return response()->json(['message'=>'User not Authenticated!!', 'status'=>401], 404);
                            /* $userData = array(
                                'name'     =>  'Full Name',
                                // 'lname'     =>  'Last name',
                                'phone'     =>  $data['phone'],
                                'email'     =>  $data['email'],
                                'password'  =>  $this->utility->generatePassword(),
                                'gLocatorID'    =>  (string)$data['user_id'],
                                'role_id'       =>  1
                            );
                            $user = $this->utility->createUser($userData);
                            $data['user_id'] = $user->id; */
                        }

                        //return $data;
                        event(new \App\Events\NewPowerTransactionEvent($data));

                        $resp['status'] = 1;
                        $resp['msg'] = $meterInfo['msg'];
                        $resp['token'] = $meterInfo['status'];
                        $resp['serviceProvider'] = $serviceInfo->name;
                        $resp['amount'] = intval($data['amount']) + 100;
                    } else {
                        $resp['status'] = -1;
                        $resp['msg'] = 'err';
                    }
                }
            }


            return response()->json($resp);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'Power Controller - Get meter info method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 420);
        }
    }

    public function request(Request $request) {

        try{
            $request->validate([
                'pin'=>'required|string',
                'token'=>'required',
                'amount_paid'=>'required',
                'user_id'=>'required|uuid',
            ]);

            //$userID     = $request->input('user_id');
            $userID = Auth::id();
            $amountPaid = $request->input('amount_paid');
            $token      = $request->input('token');
            $pin = strval($request->input('pin'));


            $resp = array(
                'status'    =>  0,
                'msg'       =>  'Pending',
                'success'   => false
            );

            // get the last active transaction tied to the token and this users account.
            $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $token)->where('status', 0)->first();
            if(!$powerTransaction) {
                // that's actually very strange because at this point, the user has paid and has a valid token but the query above could not locate the transaction.
                // apologise to the user. Don't take the blame but make them feel special anyways.
                $resp['status'] = -1000;
                $resp['msg'] = 'Power Transaction not found.';

                return response()->json($resp);
            }

            $rUserID = $this->utility->getUserByGLocatorID($userID, $powerTransaction->email);

            $user = \App\Models\User::on('mysql::read')->findOrFail($powerTransaction->user_id);
            if(!$user){
                return response()->json(['message'=>'User not found.'], 404);
            }
            if(empty($user->transaction_pin)){
                return response()->json(['message'=>'Transaction Pin not set.'], 403);
            }

            if(Hash::check($pin, $user->transaction_pin))
            {
                $serviceName = \App\Models\Service::on('mysql::read')->find($powerTransaction->service_id);
                $wallet = Wallet::on('mysql::write')->where('user_id', $user->id)->first();
                $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
                $current_balance = intval($wallet->balance);
                if($current_balance > (intval($powerTransaction->amount) + intval($serviceName->service_charge))){

                    $description = $serviceName->name . ' N ' . $powerTransaction->amount_paid . ' to ' . $powerTransaction->meter_num;
                    //app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $powerTransaction->amount, $new_balance, 2, $description, 1, $powerTransaction->transaction_id);
                    WalletTransaction::on('mysql::write')->create([
                        'wallet_id'=>$user->wallet->id,
                        'type'=>'Debit',
                        'amount'=>$powerTransaction->amount,
                        'description'=>'Power bill payment',
                        /* 'receiver_account_number'=>$acc->account_number,
                        'receiver_name'=>$user->name, */
                        'transfer'=>false,
                        'reference'=>$powerTransaction->transaction_id,
                        'status'=>'success',
                    ]);
                    $powerTransaction->update(['amount_paid' => ($powerTransaction->amount + $serviceName->service_charge)]);
                    $powerTransaction->update([
                        'status'        =>  1,
                        'amount_paid'   =>  $amountPaid
                    ]);
                    // oh yeah we got the transaction details. Simply parse the transaction details to the power vending event handler to dispense the token.
                    //event(new \App\Events\NewPowerVendEvent($powerTransaction, $amountPaid, $token));
                    $this->utility->processPowerTransaction($powerTransaction, $amountPaid, $token);


                    // as of now the event listener must have updated transaction status in the database so go get it.
                    $updatedTransaction = \App\Models\PowerTransaction::on('mysql::read')->find($powerTransaction->id);
                    if($updatedTransaction->status != 2) {
                        // something must have gone wrong while trying to dispense.
                        $resp['status'] = -2000;
                        $resp['msg'] = 'Failed.';
                    } else {
                        $new_balance = $current_balance - intval($powerTransaction->amount + $serviceName->service_charge);
                        $wallet->update(['balance' => $new_balance]);
                        $resp['status'] = 2000;
                        $resp['success'] = true;
                        $resp['msg'] = array('token' => $updatedTransaction->token, 'units' => $updatedTransaction->units, 'amountPaid' => $updatedTransaction->amount_paid, 'transID' => $updatedTransaction->transaction_id);
                    }
                }else{
                    WalletTransaction::on('mysql::write')->create([
                        'wallet_id'=>$user->wallet->id,
                        'type'=>'Debit',
                        'amount'=>$powerTransaction->amount,
                        'description'=>'Power bill payment',
                        /* 'receiver_account_number'=>$acc->account_number,
                        'receiver_name'=>$user->name, */
                        'transfer'=>false,
                        'reference'=>$powerTransaction->transaction_id,
                        'status'=>'failed',
                    ]);
                    $resp['status'] = -1;
                    $resp['msg'] = 'Insufficient funds. Please TopUp your wallet.';
                }

                return response()->json($resp);
            }else{
                return response()->json(['message'=>'Incorrect Pin!'], 404);
            }
        }catch(ValidationException $e){
            return response()->json(['message'=>$e->getMessage(), 'errors'=>$e->errors()], 420);
        }
    }

    public function processPowerRequest(\App\Models\PowerTransaction $powerTransaction, $amountPaid, $token) {
        $serviceName = \App\Models\Service::on('mysql::read')->find($powerTransaction->service_id);
        $powerString = app('App\Http\Controllers\UtilityController')->generatePowerVendAPIString(substr($powerTransaction->transaction_id, -12), $powerTransaction->meter_num, $serviceName->name, intval($powerTransaction->amount), $powerTransaction->access_token, env('MODE'));
        // $powerString = app('App\Http\Controllers\UtilityController')->generatePowerVendAPIString(substr($powerTransaction->transaction_id, -12), $powerTransaction->meter_num, $serviceName->name, intval($powerTransaction->amount), $powerTransaction->token, env('MODE'));
        $powerHash = app('App\Http\Controllers\UtilityController')->hashAPIString($powerString, env('MODE'));

        if($powerTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::on('mysql::write')->find($powerTransaction->user_id);
            $current_balance = $user->wallet->balance;
            $new_balance = $current_balance - intval($powerTransaction->amount + $serviceName->service_charge);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $serviceName->name . ' N ' . $powerTransaction->amount_paid . ' to ' . $powerTransaction->meter_num;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $powerTransaction->amount, $new_balance, 2, $description, 1, $powerTransaction->transaction_id);
            $powerTransaction->update(['amount_paid' => ($powerTransaction->amount + $serviceName->service_charge)]);
        }
        $vendResult = $this->vendPower($powerTransaction->meter_num, substr($powerTransaction->transaction_id, -12), $serviceName->name, $powerTransaction->access_token, intval($powerTransaction->amount), $powerTransaction->phone, $powerTransaction->email, $powerHash);
    }

    public function vendPower($meterNo, $referenceID, $disco, $accessToken, $amount, $phone, $email, $hash) {
        $respFormat = "json";
        $requestTimeStamp = date('Y-m-d H:i:s');
        $vendorURL = env('VENDOR_TEST_URL');
        $mode = env('MODE');
        if($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        $url = $vendorURL . "/vend_power.php?";
        $url .= "vendor_code=" . urlencode(env('VENDOR_CODE')) . "&meter=" . urlencode($meterNo) . "&reference_id=" . urlencode($referenceID) . "&response_format=" . urlencode($respFormat) . "&disco=" . urlencode($disco) . "&access_token=" . urlencode($accessToken) . "&amount=" . urlencode($amount) . "&phone=" . urlencode($phone) . "&email=" . urlencode($email) . "&hash=" . urlencode($hash);

        Log::info('Veding power using url: ');
        Log::info($url);

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $request = curl_exec($ch);
        if(curl_errno($ch)) {
            Log::info('cURL error occured while trying to dispense airtime.');
            Log::error(curl_error($ch));

            $status['status'] = curl_errno($ch);
            $status['msg'] = curl_error($ch);

            $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
            $powerTransaction->update([
                'status' => 3
            ]);
            $apiID = app('App\Http\Controllers\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
            $apiRequestData = array(
                'request'               => 'API ' . $disco . ' N' . intval($amount) . ' to ' . $meterNo,
                'response'              =>  $status['message'],
                'request_timestamp'     =>  $requestTimeStamp,
                'response_timestamp'    =>  date('Y-m-d H:i:s'),
                'api_id'                =>  $apiID,
                'status'                =>  0,
                'receiver'              =>  $meterNo,
                'ref'                   =>  'Failed',
                'response_hash'         =>  'Failed'
            );
            event(new \App\Events\ApiRequestEvent($apiRequestData));
        } else {
            if ($request) {
                $result = json_decode(app('App\Http\Controllers\UtilityController')->purifyJSON($request), true);
                Log::info('Gotten result from irecharge api');
                Log::info($result);

                if($result['status'] == '00') {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
                    $powerTransaction->update([
                        'token' =>  $result['meter_token'],
                        'units' =>  $result['units'],
                        'status' => 2
                    ]);
                    // also update api requests table.
                    $apiID = app('App\Http\Controllers\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\UtilityController')->checkAPITransactionStatus($apiID, $meterNo);
                    $apiRequestData = array(
                        'request'               => 'API ' . $disco . ' N' . intval($amount) . ' to ' . $meterNo,
                        'response'              =>  $result['message'],
                        'request_timestamp'     =>  $requestTimeStamp,
                        'response_timestamp'    =>  date('Y-m-d H:i:s'),
                        'api_id'                =>  $apiID,
                        'status'                =>  1,
                        'receiver'              =>  $meterNo,
                        'ref'                   =>  $result['ref'],
                        'response_hash'         =>  $result['response_hash']
                    );
                    event(new \App\Events\ApiRequestEvent($apiRequestData));
                    event(new \App\Events\UpdateWalletEvent($apiID, $result['wallet_balance']));
                    try {
                        Mail::to($email)->send(new \App\Mail\PowerVendMail($powerTransaction));
                    } catch(\Exception $ex) {
                        // mail was probably not sent to the customer.
                        // log this as a failed e-mail to failed email transaction table.
                        $failedEmailData = array(
                            'transaction_type'  => 'power',
                            'transaction_id'    => $powerTransaction->id,
                            'trials'            => 0
                        );
                        FailedEmail::on('mysql::write')->create($failedEmailData);
                    }
                    $sms = "The " . $disco ." token: " . $powerTransaction->token . " ref: " . $powerTransaction->transaction_id . ".Hope to see you again. https://ebuy.sundiatapost.com Help-line: 08033083361";
                    if($mode == 2) {
                        app('App\Http\Controllers\UtilityController')->sendSMS($sms, $phone);
                    }
                } else {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('token', $accessToken)->first();
                    $powerTransaction->update([
                        'units' =>  $result['status'],
                        'status' => 3
                    ]);

                    $apiID = app('App\Http\Controllers\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\UtilityController')->checkAPITransactionStatus($apiID, $meterNo);
                    $apiRequestData = array(
                        'request'               => 'API ' . $disco . ' N' . intval($amount) . ' to ' . $meterNo,
                        'response'              =>  $result['message'],
                        'request_timestamp'     =>  $requestTimeStamp,
                        'response_timestamp'    =>  date('Y-m-d H:i:s'),
                        'api_id'                =>  $apiID,
                        'status'                =>  0,
                        'receiver'              =>  $meterNo,
                        'ref'                   =>  'Failed',
                        'response_hash'         =>  'Failed'
                    );
                    event(new \App\Events\ApiRequestEvent($apiRequestData));
                }
            } else {
                $status['status'] = curl_errno($ch);
                $status['msg'] = curl_error($ch);
                $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('token', $accessToken)->first();
                $powerTransaction->update([
                    'status' => 3
                ]);
            }
        }
        curl_close($ch);
    }
}
