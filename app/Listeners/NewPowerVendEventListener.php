<?php

namespace App\Listeners;

use App\Events\NewPowerVendEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NewPowerVendEventListener
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
     * @param  NewPowerVendEvent  $event
     * @return void
     */
    public function handle(NewPowerVendEvent $event)
    {
        Log::info('Lets begin vending process...');
        $token = $event->token;
        $amountPaid = $event->amountPaid;
        $powerTransaction = $event->powerTransaction;

        // $referenceID, $receiver, $disco, $amount, $accessToken, 45700209468
        $serviceName = \App\Models\Service::on('mysql::read')->find($powerTransaction->service_id);
        $powerString = app('App\Http\Controllers\Apis\UtilityController')->generatePowerVendAPIString(substr($powerTransaction->transaction_id, -12), $powerTransaction->meter_num, $serviceName->name, intval($powerTransaction->amount), $powerTransaction->token, env('MODE'));
        $powerHash = app('App\Http\Controllers\Apis\UtilityController')->hashAPIString($powerString, env('MODE'));

        /* if($powerTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::find($powerTransaction->user_id);
            $current_balance = $user->wallet->balance;
            $new_balance = $current_balance - intval($powerTransaction->amount + $serviceName->service_charge);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $serviceName->name . ' N ' . $powerTransaction->amount_paid . ' to ' . $powerTransaction->meter_num;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $powerTransaction->amount, $new_balance, 2, $description, 1, $powerTransaction->transaction_id);
            $powerTransaction->update(['amount_paid' => ($powerTransaction->amount + $serviceName->service_charge)]);
        } */

        $vendResult = $this->vendPower($powerTransaction->meter_num, substr($powerTransaction->transaction_id, -12), $serviceName->name, $powerTransaction->token, intval($powerTransaction->amount), $powerTransaction->phone, $powerTransaction->email, $powerHash);
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
            CURLOPT_TIMEOUT => 60,
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

            $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('token', $accessToken)->first();
            $powerTransaction->update([
                'status' => 3
            ]);
            $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
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
                $result = json_decode(app('App\Http\Controllers\Apis\UtilityController')->purifyJSON($request), true);
                Log::info('Gotten result from irecharge api');
                Log::info($result);

                if($result['status'] == '00') {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('token', $accessToken)->first();
                    $powerTransaction->update([
                        'token' =>  $result['meter_token'],
                        'units' =>  $result['units'],
                        'status' => 2
                    ]);
                    // also update api requests table.
                    $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\Apis\UtilityController')->checkAPITransactionStatus($apiID, $meterNo);
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
                        \App\Models\FailedEmail::on('mysql::write')->create($failedEmailData);
                    }
                    $sms = "The " . $disco ." token: " . $powerTransaction->token . " ref: " . $powerTransaction->transaction_id . ".Hope to see you again. https://sharpsharp.ng Help-line: 08033083361";
                    if($mode == 2) {
                        app('App\Http\Controllers\Apis\UtilityController')->sendSMS($sms, $phone);
                    }
                } else {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('token', $accessToken)->first();
                    $powerTransaction->update([
                        'units' =>  $result['status'],
                        'status' => 3
                    ]);

                    $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = app('App\Http\Controllers\Apis\UtilityController')->checkAPITransactionStatus($apiID, $meterNo);
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
