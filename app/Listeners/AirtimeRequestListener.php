<?php

namespace App\Listeners;

use App\Events\AirtimeRequestEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AirtimeRequestListener
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
     * @param  AirtimeRequestEvent  $event
     * @return void
     */
    public function handle(AirtimeRequestEvent $event)
    {
        $airtimeTransaction = $event->airtimeTransaction;

        // now lets start the intricacies of dispensing airtime.

        // first lets update the transaction status to processing.
        \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
            'status'    =>  1
        ]);

        // prepare parameters needed by irecharge api.
        $serviceName = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($airtimeTransaction->service_id);
        if($serviceName == "9mobile") {
            $serviceName = "Etisalat";
        }
        $transactionID = substr($airtimeTransaction->transaction_id, -12);
        $apiString = app('App\Http\Controllers\Apis\UtilityController')->generateAirtimeAPIString($transactionID, $airtimeTransaction->phone, $serviceName, intval($airtimeTransaction->amount), env('MODE'));
        $hash = app('App\Http\Controllers\Apis\UtilityController')->hashAPIString($apiString, env('MODE'));

        // deduct users wallet if user is paying through wallet.
        /* if($airtimeTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::where('email', $airtimeTransaction->email)->first();
            $current_balance = $user->wallet->balance;
            $new_balance = floatval($current_balance) - floatval($airtimeTransaction->amount);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $serviceName . ' N ' . intval($airtimeTransaction->amount) . ' to ' . $airtimeTransaction->phone;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $airtimeTransaction->amount, $new_balance, 2, $description, 1, $airtimeTransaction->transaction_id);
            $airtimeTransaction->update(['amount_paid' => $airtimeTransaction->amount]);
        } */
        //lets now send request to irecharge api to vend the airtime for us.
        $vendResult = $this->vendAirtime($airtimeTransaction, $serviceName, intval($airtimeTransaction->amount_paid), $airtimeTransaction->phone, $airtimeTransaction->email, $transactionID, $hash, $airtimeTransaction->service_id);
        $requestTimeStamp = date('Y-m-d H:i:s');
        if($vendResult['status'] == -1 || (1 <= $vendResult['status']) && ($vendResult['status'] <= 88)) {
            // catch error probably another cURL error or cURL executed and returned with an error.;
            // the exception has been logged to the application console. Just go ahead to update the transaction as a failed transaction and update the api requests table as well.
            \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
                'status'    =>  3
            ]);
            $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($airtimeTransaction->service_id, 1);
            $apiRequestData = array(
                'request'               => 'API ' . $serviceName . ' N' . intval($airtimeTransaction->amount) . ' to ' . $airtimeTransaction->phone,
                'response'              =>  $vendResult['msg'],
                'request_timestamp'     =>  $requestTimeStamp,
                'response_timestamp'    =>  date('Y-m-d H:i:s'),
                'api_id'                =>  $apiID,
                'status'                =>  0,
                'receiver'              =>  $airtimeTransaction->phone,
                'ref'                   =>  '',
                'response_hash'         =>  ''
            );
            event(new \App\Events\ApiRequestEvent($apiRequestData));
        } else {
            \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
                'status'    =>  2
            ]);
        }
    }

    public function vendAirtime(\App\Models\AirtimeTransaction $airtimeTransaction, $serviceName, $amount, $phone, $email, $transactionID, $hash, $serviceID) {
        $status = array(
            'status'   =>   0,
            'msg'      =>   'Pending.'
        );
        $result = array();
        $mode = env('MODE');
        $requestTimeStamp = date('Y-m-d H:i:s');
        $vendorURL = env('VENDOR_TEST_URL');
        if($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        $url = $vendorURL . "/vend_airtime.php?vendor_code=".env('VENDOR_CODE')."&vtu_network=".$serviceName."&vtu_amount=".$amount."&vtu_number=".$phone."&vtu_email=".$email."&reference_id=".$transactionID."&hash=".$hash;
        Log::info('vend url');
        Log::info($url);
        try {
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

                \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
                    'status'    =>  3
                ]);
                $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($airtimeTransaction->service_id, 1);
                $apiRequestData = array(
                    'request'               => 'API ' . $serviceName . ' N' . intval($airtimeTransaction->amount) . ' to ' . $airtimeTransaction->phone,
                    'response'              =>  $status['msg'],
                    'request_timestamp'     =>  $requestTimeStamp,
                    'response_timestamp'    =>  date('Y-m-d H:i:s'),
                    'api_id'                =>  $apiID,
                    'status'                =>  0,
                    'receiver'              =>  $airtimeTransaction->phone,
                    'ref'                   =>  '',
                    'response_hash'         =>  ''
                );
                event(new \App\Events\ApiRequestEvent($apiRequestData));
            } else {
                if ($request) {
                    $result = json_decode(app('App\Http\Controllers\Apis\UtilityController')->purifyJSON($request), true);
                    Log::info('Gotten result from irecharge api');
                    Log::info($result);

                    if($result['status'] == '00') {
                        // fire event to notify user.
                        // also update the necessary tables i.e. airtime transactions and api requests tables respectively.
                        // also update wallet balance
                        $requestTimeStamp = date('Y-m-d H:i:s');
                        $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($serviceID, 1);
                        $apiRequestData = array(
                            'request'               => 'API ' . $serviceName . ' N' . intval($amount) . ' to ' . $phone,
                            'response'              =>  $result['message'],
                            'request_timestamp'     =>  $requestTimeStamp,
                            'response_timestamp'    =>  date('Y-m-d H:i:s'),
                            'api_id'                =>  $apiID,
                            'status'                =>  1,
                            'receiver'              =>  $phone,
                            'ref'                   =>  $result['ref'],
                            'response_hash'         =>  $result['response_hash']
                        );
                        event(new \App\Events\ApiRequestEvent($apiRequestData));
                        event(new \App\Events\UpdateWalletEvent($apiID, $result['wallet_balance']));
                        try {
                            Mail::to($email)->send(new \App\Mail\AirtimeVendMail($airtimeTransaction));
                        } catch(\Exception $ex) {
                            // mail was probably not sent to the customer.
                            // log this as a failed e-mail to failed email transaction table.
                            $failedEmailData = array(
                                'transaction_type'  => 'airtime',
                                'transaction_id'    => $airtimeTransaction->id,
                                'trials'            => 0
                            );
                            \App\Models\FailedEmail::on('mysql::write')->create($failedEmailData);
                        }
                    } else {
                        $status['status'] = $result['status'];
                        $status['msg'] = $result['message'];

                        $requestTimeStamp = date('Y-m-d H:i:s');
                        $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($serviceID, 1);
                        $apiRequestData = array(
                            'request'               => 'API ' . $serviceName . ' N' . intval($amount) . ' to ' . $phone,
                            'response'              =>  $result['message'],
                            'request_timestamp'     =>  $requestTimeStamp,
                            'response_timestamp'    =>  date('Y-m-d H:i:s'),
                            'api_id'                =>  $apiID,
                            'status'                =>  0,
                            'receiver'              =>  $phone,
                            'ref'                   =>  'Failed',
                            'response_hash'         =>  'Failed'
                        );
                        event(new \App\Events\ApiRequestEvent($apiRequestData));
                    }
                } else {
                    $status['status'] = curl_errno($ch);
                    $status['msg'] = curl_error($ch);
                    \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
                        'status'    =>  3
                    ]);
                }
            }
            curl_close($ch);
        } catch(\Exception $ex) {
            Log::info('Error occured while trying to start cURL to dispense airtime. cURL was probably never successfully initiated.');
            Log::error($ex);

            $status['status'] = -1;
            $status['msg'] = $ex;
            \App\Models\AirtimeTransaction::on('mysql::write')->where('id', $airtimeTransaction->id)->update([
                'status'    =>  3
            ]);
        }

        return $status;
    }
}
