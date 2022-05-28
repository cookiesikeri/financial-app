<?php

namespace App\Listeners;

use App\Events\DataRequestEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class DataRequestEventListener
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
     * @param  DataRequestEvent  $event
     * @return void
     */
    public function handle(DataRequestEvent $event)
    {
        Log::info('Lets begin dispensing...');
        $dataTransaction = $event->dataTransaction;

        $requestedBundle = \App\Models\DataBundle::on('mysql::read')->find($dataTransaction->data_bundles_id);

        Log::info($requestedBundle);

        \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
            'status'    =>  1
        ]);

        $serviceName = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($dataTransaction->service_id);
        $transactionID = substr($dataTransaction->transaction_id, -12);

        $apiString = app('App\Http\Controllers\Apis\UtilityController')->generateDataAPIString($transactionID, $dataTransaction->phone, $serviceName, $requestedBundle->code, env('MODE'));
        $hash = app('App\Http\Controllers\Apis\UtilityController')->hashAPIString($apiString, env('MODE'));
        Log::info('Requesting data for transaction: ');
        Log::info($dataTransaction);

        /* if($dataTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::where('email', $dataTransaction->email)->first();
            $current_balance = $user->wallet->balance;
            $new_balance = $current_balance - intval($dataTransaction->amount);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $serviceName . ' N ' . $dataTransaction->amount . ' to ' . $dataTransaction->phone;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $dataTransaction->amount, $new_balance, 2, $description, 1, $dataTransaction->transaction_id);
            $dataTransaction->update(['amount_paid' => $dataTransaction->amount]);
        } */

        $vendResult = $this->vendData($dataTransaction, $serviceName, $requestedBundle->code, $dataTransaction->phone, $dataTransaction->email, $transactionID, $hash, $dataTransaction->service_id, $requestedBundle->name);
        $requestTimeStamp = date('Y-m-d H:i:s');
        if((1 <= $vendResult['status']) && ($vendResult['status'] <= 88)) {
            \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
                'status'    =>  3
            ]);
            $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($dataTransaction->service_id, 1);
            $apiRequestData = array(
                'request'               => 'API ' . $requestedBundle->name . ' to ' . $dataTransaction->phone,
                'response'              =>  $vendResult['msg'],
                'request_timestamp'     =>  $requestTimeStamp,
                'response_timestamp'    =>  date('Y-m-d H:i:s'),
                'api_id'                =>  $apiID,
                'status'                =>  0,
                'receiver'              =>  $dataTransaction->phone,
                'ref'                   =>  '',
                'response_hash'         =>  ''
            );
            event(new \App\Events\ApiRequestEvent($apiRequestData));
        } else {
            \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
                'status'    =>  2
            ]);
        }
    }

    public function vendData(\App\Models\DataTransaction $dataTransaction, $serviceName, $code, $phone, $email, $transactionID, $hash, $serviceID, $bundleName) {
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
        $url = $vendorURL . "/vend_data.php?vendor_code=".env('VENDOR_CODE')."&vtu_network=".$serviceName."&vtu_data=".$code."&vtu_number=".$phone."&vtu_email=".$email."&reference_id=".$transactionID."&hash=".$hash;
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
            Log::info('cURL error occured while trying to dispense data.');
            Log::error(curl_error($ch));

            $status['status'] = curl_errno($ch);
            $status['msg'] = curl_error($ch);

            \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
                'status'    =>  3
            ]);
            $requestedBundle = \App\Models\DataBundle::on('mysql::read')->find($dataTransaction->data_bundles_id);
            $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($serviceID, 1);
            $apiRequestData = array(
                'request'               => 'API ' . $requestedBundle->name . ' to ' . $dataTransaction->phone,
                'response'              =>  $status['msg'],
                'request_timestamp'     =>  $requestTimeStamp,
                'response_timestamp'    =>  date('Y-m-d H:i:s'),
                'api_id'                =>  $apiID,
                'status'                =>  0,
                'receiver'              =>  $dataTransaction->phone,
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
                    // also update the necessary tables i.e. data transactions and api requests tables respectively.
                    // also update wallet balance
                    $requestTimeStamp = date('Y-m-d H:i:s');
                    $apiID = app('App\Http\Controllers\Apis\UtilityController')->resolveServiceNameFromID($serviceID, 1);
                    $apiRequestData = array(
                        'request'               => 'API ' . $bundleName . ' to ' . $phone,
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
                    event(new \App\Events\UpdateWalletEvent($apiID, $result['wallet_balance']));
                    try {
                        Mail::to($email)->send(new \App\Mail\DataVendMail($dataTransaction));
                    } catch(\Exception $ex) {
                        // mail was probably not sent to the customer.
                        // log this as a failed e-mail to failed email transaction table.
                        $failedEmailData = array(
                            'transaction_type'  => 'data',
                            'transaction_id'    => $dataTransaction->id,
                            'trials'            => 0
                        );
                        \App\Models\FailedEmail::on('mysql::write')->create($failedEmailData);
                    }
                } else {
                    $status['status'] = $result['status'];
                    $status['msg'] = $result['message'];
                    \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
                        'status'    =>  3
                    ]);
                }
            } else {
                $status['status'] = curl_errno($ch);
                $status['msg'] = curl_error($ch);
                \App\Models\DataTransaction::on('mysql::write')->find($dataTransaction->id)->update([
                    'status'    =>  3
                ]);
            }
        }
        curl_close($ch);

        return $status;
    }
}
