<?php

namespace App\Listeners;

use App\Events\NewTVVendEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NewTVVendEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewTVVendEvent  $event
     * @return void
     */
    public function handle(NewTVVendEvent $event)
    {
        $tvTransaction = $event->tvTransaction;
        $tvProvider = \App\Models\Service::on('mysql::read')->find($tvTransaction->service_id);
        $tvBundle = \App\Models\TVBundle::on('mysql::read')->find($tvTransaction->tv_bundles_id);
        $service = \App\Models\Service::on('mysql::read')->find($tvTransaction->service_id);

        /* if($tvTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::find($tvTransaction->user_id);
            $current_balance = $user->wallet->balance;
            $new_balance = $current_balance - intval($tvBundle->amount + $service->service_charge);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $tvBundle->name . ' N ' . $tvTransaction->amount . ' to ' . $tvTransaction->smartcard_num;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $tvTransaction->amount, $new_balance, 2, $description, 1, $tvTransaction->transaction_id);
            $tvTransaction->update(['amount_paid' => ($tvBundle->amount + $service->service_charge)]);
        } */

        $proposedAmount = intval($tvTransaction->amount_paid + $service->service_charge);
        Log::info('Proposed amount: ' . $proposedAmount);
        if(strtolower($tvBundle->name) == "startimes") {
            $apiString = app('App\Http\Controllers\Apis\UtilityController')->generateTVVendApiString(substr($tvTransaction->transaction_id, -12), $tvTransaction->smartcard_num, $tvProvider->name, $tvBundle->code, $tvTransaction->access_token, env('MODE'));
        } else {
            $apiString = app('App\Http\Controllers\Apis\UtilityController')->generateTVVendApiString(substr($tvTransaction->transaction_id, -12), $tvTransaction->smartcard_num, $tvProvider->name, $tvBundle->code, $tvTransaction->access_token, env('MODE'));
        }

        $hash = app('App\Http\Controllers\Apis\UtilityController')->hashAPIString($apiString, env('MODE'));

        if(strtolower($tvBundle->name) == "startimes") {
            $startimesAmount = $tvTransaction->amount;
            Log::info('StarTimes amount: ' . $startimesAmount);
            $vendTV = $this->vendTV($tvTransaction, $tvProvider->name, $tvBundle->code, $startimesAmount, $hash, $tvBundle->name);
        } else {
            $vendTV = $this->vendTV($tvTransaction, $tvProvider->name, $tvBundle->code, 0, $hash, $tvBundle->name);
        }

    }

    public function vendTV(\App\Models\TVTransaction $tvTransaction, $tvProvider, $serviceCode, $startimesAmount = 0, $hash, $package) {
        $respFormat = "json";
        $requestTimeStamp = date('Y-m-d H:i:s');
        $mode = env('MODE');
        $vendorURL = env('VENDOR_TEST_URL');
        if($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        $url = $vendorURL . "/vend_tv.php";
        if($startimesAmount === 0) {
            $url .= "?vendor_code=".urlencode(env('VENDOR_CODE'))."&smartcard_number=".urlencode($tvTransaction->smartcard_num)."&reference_id=".urlencode(substr($tvTransaction->transaction_id, -12))."&response_format=".urlencode($respFormat)."&tv_network=".urlencode($tvProvider)."&access_token=".urlencode($tvTransaction->access_token)."&service_code=".urlencode($serviceCode)."&phone=".urlencode($tvTransaction->phone)."&email=".urlencode($tvTransaction->email)."&hash=".urlencode($hash);
        } else {
            Log::info('using startimes URL');
            $url .= "?vendor_code=".urlencode(env('VENDOR_CODE'))."&smartcard_number=".urlencode($tvTransaction->smartcard_num)."&reference_id=".urlencode(substr($tvTransaction->transaction_id, -12))."&response_format=".urlencode($respFormat)."&tv_network=".urlencode($tvProvider)."&access_token=".urlencode($tvTransaction->access_token)."&service_code=".urlencode($serviceCode)."&startimes_amount=".urlencode($startimesAmount)."&phone=".urlencode($tvTransaction->phone)."&email=".urlencode($tvTransaction->email)."&hash=".urlencode($hash);
        }
        Log::info('Vend request string');
        Log::info($url);

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $request = curl_exec($ch);
        if(curl_errno($ch)) {
            Log::info('cURL error occured while trying to dispense tv.');
            Log::error(curl_error($ch));

            $status['status'] = curl_errno($ch);
            $status['msg'] = curl_error($ch);

            $tvTransaction->update([
                'status' => 3
            ]);

            $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($tvTransaction->service_id, 1);
            // check if there's a record for this transaction on the api requests table.
            $apiRequestStatus = app('App\Http\Controllers\Apis\UtilityController')->checkAPITransactionStatus($apiID, $tvTransaction->smartcard_num);
            $apiRequestData = array(
                'request'               => 'API ' . $tvProvider . $package . ' to ' . $tvTransaction->smartcard_num,
                'response'              =>  $status['message'],
                'request_timestamp'     =>  $requestTimeStamp,
                'response_timestamp'    =>  date('Y-m-d H:i:s'),
                'api_id'                =>  $apiID,
                'status'                =>  0,
                'receiver'              =>  $tvTransaction->smartcard_num,
                'ref'                   =>  'Failed',
                'response_hash'         =>  'Failed'
            );
            event(new \App\Events\ApiRequestEvent($apiRequestData));
        } else {
            if ($request) {
                $result = json_decode(app('App\Http\Controllers\Apis\UtilityController')->purifyJSON($request), true);
                Log::info('Gotten result from irecharge api');
                Log::info($result);

                if($result['status'] == '00') {
                    $tvTransaction->update([
                        'status' => 2
                    ]);
                    // also update api requests table.
                    $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($tvTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\Apis\UtilityController')->checkAPITransactionStatus($apiID, $tvTransaction->smartcard_num);

                    $apiRequestData = array(
                        'request'               => 'API ' . $tvProvider . $package . ' to ' . $tvTransaction->smartcard_num,
                        'response'              =>  $result['message'],
                        'request_timestamp'     =>  $requestTimeStamp,
                        'response_timestamp'    =>  date('Y-m-d H:i:s'),
                        'api_id'                =>  $apiID,
                        'status'                =>  1,
                        'receiver'              =>  $tvTransaction->smartcard_num,
                        'ref'                   =>  random_int(11111111, 9999999999),
                        'response_hash'         =>  $result['response_hash']
                    );
                    event(new \App\Events\ApiRequestEvent($apiRequestData));
                    event(new \App\Events\UpdateWalletEvent($apiID, $result['wallet_balance']));
                    try {
                        Mail::to($tvTransaction->email)->send(new \App\Mail\TvVendMail($tvTransaction));
                    } catch(\Exception $ex) {
                        // mail was probably not sent to the customer.
                        // log this as a failed e-mail to failed email transaction table.
                        $failedEmailData = array(
                            'transaction_type'  => 'tv',
                            'transaction_id'    => $tvTransaction->id,
                            'trials'            => 0
                        );
                        \App\Models\FailedEmail::on('mysql::write')->create($failedEmailData);
                    }
                    $sms = "Your " . $package ." subscription was successful. ref: " . $tvTransaction->transaction_id . ".Hope to see you again. https://ebuy.sundiatapost.com Help-line: 08033083361";
                    if($mode == 2) {
                        //app('App\Http\Controllers\Apis\UtilityController')->sendSMS($sms, $tvTransaction->phone);
                    }
                } else {
                    $tvTransaction->update([
                        'status' => 3
                    ]);

                    $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($tvTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\Apis\UtilityController')->checkAPITransactionStatus($apiID, $tvTransaction->smartcard_num);
                    $apiRequestData = array(
                        'request'               => 'API ' . $tvProvider . $package . ' to ' . $tvTransaction->smartcard_num,
                        'response'              =>  $result['message'],
                        'request_timestamp'     =>  $requestTimeStamp,
                        'response_timestamp'    =>  date('Y-m-d H:i:s'),
                        'api_id'                =>  $apiID,
                        'status'                =>  0,
                        'receiver'              =>  $tvTransaction->smartcard_num,
                        'ref'                   =>  'Failed',
                        'response_hash'         =>  'Failed'
                    );
                    event(new \App\Events\ApiRequestEvent($apiRequestData));
                }
            } else {
                $status['status'] = curl_errno($ch);
                $status['msg'] = curl_error($ch);
                $tvTransaction->update([
                    'status' => 3
                ]);
            }
        }
        curl_close($ch);
    }
}
