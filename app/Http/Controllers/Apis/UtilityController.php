<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UtilityController extends Controller
{
    public function generateTransactionID($service)
    {
        $prefix = '';
        $transactionID = 0;
        switch ($service) {
            case 1:
                //airtime
                $id = random_int(1000, 999999999999);
                $length = strlen((string)$id);
                if ($length < 12 || $length > 12) {
                    $id = $this->generateTransactionID(1);
                }
                $transactionID = 'AIR-' . $id;
                break;
            case 2:
                // data
                $id = random_int(1000, 999999999999);
                $length = strlen((string)$id);
                if ($length < 12 || $length > 12) {
                    $id = $this->generateTransactionID(2);
                }
                $transactionID = 'DAT-' . $id;
                break;
            case 3:
                // Power
                $id = random_int(1000, 999999999999);
                $length = strlen((string)$id);
                if ($length < 12 || $length > 12) {
                    $id = $this->generateTransactionID(3);
                }
                $transactionID = 'POW-' . $id;
                break;
            case 4:
                // TV
                $id = random_int(1000, 999999999999);
                $length = strlen((string)$id);
                if ($length < 12 || $length > 12) {
                    $id = $this->generateTransactionID(4);
                }
                $transactionID = 'TV-' . $id;
                break;
        }

        return $transactionID;
    }

    public function getTodaysPendingTransactionsCount($userID, $serviceID)
    {
        $pendingTransactions = $this->getTodaysPendingTransactions($userID, $serviceID);
        if ($pendingTransactions == "s404" || $pendingTransactions == "st404") {
            return $pendingTransactions;
        }
        return count($pendingTransactions->toArray());
    }

    public function getTodaysPendingTransactions($userID, $serviceID)
    {
        $transactionTable = $this->resolveTransactionTable($serviceID);

        if ($transactionTable == "s404" || $transactionTable == "st404") {
            return $transactionTable;
        }

        $today = date("Y-m-d");
        $pendingTransactions = $transactionTable::on('mysql::read')->where('user_id', $userID)
            ->where('service_id', $serviceID)
            ->where('status', '<>', 2)
            ->where('date_created', 'LIKE', '%' . $today . '%')
            ->where('date_modified', 'LIKE', '%' . $today . '%')
            ->get();
        return $pendingTransactions;
    }

    public function resolveTransactionTable($serviceID)
    {
        $transactionTable = 'unknown';
        $service = strtolower($this->resolveServiceFromID($serviceID));

        switch ($service) {
            case 'airtime':
                $transactionTable = '\App\Models\AirtimeTransaction';
                break;
            case 'data':
                $transactionTable = '\App\Models\DataTransaction';
                break;
            default:
                $transactionTable = $service;
        }

        return $transactionTable;
    }

    public function resolveServiceFromID($serviceID)
    {
        $service = \App\Models\Service::on('mysql::read')->find($serviceID);

        if (!$service) {
            return "s404";
        }

        $serviceType = \App\Models\ServiceType::on('mysql::read')->find($service->service_type_id);

        if (!$serviceType) {
            return "st404";
        }

        Log::info('Service name: ' . $serviceType->name);
        return $serviceType->name;
    }

    public function resolveServiceNameFromID($serviceID, $needsAPI = 0)
    {
        $service = \App\Models\Service::on('mysql::read')->find($serviceID);

        if (!$service) {
            return "s404";
        }

        if (!$needsAPI) {
            return $service->name == "9mobile" ? "Etisalat" : $service->name;
        }

        return $service->api_id;
    }

    public function getUserByGLocatorID($gLocatorID, $email)
    {
        $user = \App\Models\User::on('mysql::write')->where('gLocatorID', $gLocatorID)->orWhere('email', $email)->first();

        if ($user && $user->count() > 0) {
            return $user->id;
        }

        return -1;
    }

    public function getUserByID($user_id)
    {
        $user = \App\Models\User::on('mysql::read')->find($user_id);

        if ($user && $user->count() > 0) {
            return $user->id;
        }

        return -1;
    }

    public function createUser($data)
    {
        $str = $data['password'];
        $data['password'] = $this->hashPassword($data['password']);
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $user = \App\Models\User::on('mysql::write')->create($data);
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        // fire event to send welcome e-mail to user.
        // \Artisan::call('user:sendmail', [
        //     'id'        => $user->id,
        //     'string'    => $str
        // ]);
        return $user;
    }

    public function generatePassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public function hashPassword($string)
    {
        return bcrypt($string);
    }

    public function testRave($ref, $mode = 1)
    {
        $resp = 0;

        $data = array(
            'txref' => $ref,
            'SECKEY' => env('FLWSECK-cc59bc8f88bd6c1d3f77b583bb6cc432-X')
        );

        // make request to endpoint using unirest.
        \Unirest\Request::verifyPeer(false);
        $headers = array('Content-Type' => 'application/json');
        $body = \Unirest\Request\Body::json($data);
        $url = "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify"; //url to staging server. please make sure to change when in production.

        if ($mode == 2) {
            $url = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify";
            $data['SECKEY'] = env('FLWSECK-cc59bc8f88bd6c1d3f77b583bb6cc432-X');
        }
        // Make `POST` request and handle response with unirest
        try {
            $response = \Unirest\Request::post($url, $headers, $body);
            \Log::info('response from flutter');
            \Log::info($response);
        } catch (\Exception $ex) {
            \Log::info('error trying to verify payment...');
            \Log::info($ex->getMessage());
            $transaction->update(['status' => 3]);
            $resp = $ex->getCode();
        }

        if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
            //confirm that the amount is the amount you wanted to charge
            if ($response->body->data->amount === $amount) {
                $resp = 100;
                $transaction->update(['amount_paid' => $amount]);
            } else {
                $transaction->update(['status' => 5]);
                return -2;
            }
        } else {
            $transaction->update(['status' => 3]);
            $resp = 404;
        }

        return $resp;
    }

    public function verifyRavePayment($ref, $transactionType, $transactionID, $mode = 1)
    {
        $amount = 0;
        $resp = 0;
        $transaction = null;
        switch ($transactionType) {
            case 'airtime':
                $transaction = \App\Models\AirtimeTransaction::on('mysql::write')->find($transactionID);
                $amount = intval($transaction->amount);
                break;
            case 'data':
                $transaction = \App\Models\DataTransaction::on('mysql::write')->find($transactionID);
                $amount = intval($transaction->amount);
                break;
            case 'power':
                $transaction = \App\Models\PowerTransaction::on('mysql::write')->find($transactionID);
                $amount = intval($transaction->amount);
                break;
            case 'tv':
                $transaction = \App\Models\TVTransaction::on('mysql::write')->find($transactionID);
                $amount = intval($transaction->amount);
                break;
        }

        $data = array(
            'txref' => $ref,
            'SECKEY' => env('FLUTTER_WAVE_SANDBOX_SECRET_KEY')
        );

        // make request to endpoint using unirest.
        \Unirest\Request::verifyPeer(false);
        $headers = array('Content-Type' => 'application/json');
        $body = \Unirest\Request\Body::json($data);
        $url = "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify"; //url to staging server. please make sure to change when in production.

        if ($mode == 2) {
            $url = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify";
            $data['SECKEY'] = env('FLUTTER_WAVE_LIVE_SECRET_KEY');
        }
        // Make `POST` request and handle response with unirest
        try {
            $response = \Unirest\Request::post($url, $headers, $body);
        } catch (\Exception $ex) {
            \Log::info('error trying to verify payment...');
            \Log::info($ex->getMessage());
            $transaction->update(['status' => 3]);
            $resp = $ex->getCode();
        }

        //check the status is success
        // return $response->body;
        if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
            //confirm that the amount is the amount you wanted to charge
            if ($response->body->data->amount === $amount) {
                $resp = 100;
                $transaction->update(['amount_paid' => $amount]);
            } else {
                $transaction->update(['status' => 5]);
                return -2;
            }
        } else {
            $transaction->update(['status' => 3]);
            $resp = 404;
        }

        return $resp;
    }

    public function verifyPayment($paymentReference, $transactionType, $transactionID, $mode = 1)
    {
        Log::info('Lets try to verify payment from paystack.');
        $verified = 0;
        $result = array();
        $key = env('PAYSTACK_SECRET_KEY');
        /* if ($mode == 2) {
            $key = env('PAYSTACK_LIVE_PRIVATE_KEY');
        } */
        $url = 'https://api.paystack.co/transaction/verify/' . $paymentReference;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            ['Authorization: Bearer ' . $key]
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $request = curl_exec($ch);

        if (curl_errno($ch)) {
            // $resp['msg'] = curl_error($ch);
            $verified = curl_errno($ch);
            Log::info('cURL error occured while trying to verify payment.');
            Log::error(curl_error($ch));
            // change status for the transaction to failed so in case the payment actually went through, it can be retried.
            switch ($transactionType) {
                case 'airtime':
                    $transaction = \App\Models\AirtimeTransaction::on('mysql::write')->find($transactionID);
                    $transaction->update(['status' => 3]);
                    break;
                case 'data':
                    $transaction = \App\Models\DataTransaction::on('mysql::write')->find($transactionID);
                    $transaction->update(['status' => 3]);
                    break;
                case 'power':
                    $transaction = \App\Models\PowerTransaction::on('mysql::write')->find($transactionID);
                    $transaction->update(['status' => 3]);
                    break;
                case 'tv':
                    $transaction = \App\Models\TVTransaction::on('mysql::write')->find($transactionID);
                    $transaction->update(['status' => 3]);
                    break;
            }
        } else {
            if ($request) {
                $result = json_decode($request, true);

                if (isset($result["data"]) && $result["data"]["status"] == "success") {
                    // at this point, payment has been verified.
                    // launch an event on a queue to send email of receipt to user.
                    $verified = 100;
                } else {
                    // $resp['msg'] = 'Transaction not found!';
                    $verified = 404;
                }
            } else {
                // $resp['msg'] = 'Unable to verify transaction!';
                $verified = 503;
            }
        }
        curl_close($ch);

        return $verified;
    }

    public function generateAirtimeAPIString($transactionID, $receiver, $serviceName, $amount, $mode = 1)
    {
        Log::info('generating api string for airtime purchase.');
        $key = env('TEST_PUBLIC_KEY');
        if ($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE') . "|" . $transactionID . "|" . $receiver . "|" . $serviceName . "|" . $amount . "|" . $key);

        return env('VENDOR_CODE') . "|" . $transactionID . "|" . $receiver . "|" . $serviceName . "|" . $amount . "|" . $key;
    }

    public function generateDataAPIString($transactionID, $receiver, $serviceName, $data, $mode = 1)
    {
        Log::info('generating api string for data purchase.');
        $key = env('TEST_PUBLIC_KEY');
        if ($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE') . "|" . $transactionID . "|" . $receiver . "|" . $serviceName . "|" . $data . "|" . $key);

        return env('VENDOR_CODE') . "|" . $transactionID . "|" . $receiver . "|" . $serviceName . "|" . $data . "|" . $key;
    }

    public function generatePowerAPIString($meterNo, $transactionID, $disco, $mode = 1)
    {
        Log::info('generating api string for power purchase.');
        $key = env('TEST_PUBLIC_KEY');
        if ($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE') . "|" . $transactionID . "|" . $meterNo . "|" . $disco . "|" . $key);

        return env('VENDOR_CODE') . "|" . $transactionID . "|" . $meterNo . "|" . $disco . "|" . $key;
    }

    public function generatePowerVendAPIString($referenceID, $receiver, $disco, $amount, $accessToken, $mode = 1)
    {
        Log::info('generating api string for power vending.');
        $key = env('TEST_PUBLIC_KEY');
        if ($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE') . "|" . $referenceID . "|" . $receiver . "|" . $disco . "|" . $amount . "|" . $accessToken . "|" . $key);

        return env('VENDOR_CODE') . "|" . $referenceID . "|" . $receiver . "|" . $disco . "|" . $amount . "|" . $accessToken . "|" . $key;
    }


    public function generateTVAPIString($transactionID, $tvNetwork, $smartCardNo, $serviceCode, $mode = 1) {
        Log::info('generating api string for tv smart card verification');
        $key = env('TEST_PUBLIC_KEY');
        if($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE')."|".$transactionID."|".$tvNetwork."|".$smartCardNo."|".$serviceCode."|".$key);

        return env('VENDOR_CODE')."|".$transactionID."|".$tvNetwork."|".$smartCardNo."|".$serviceCode."|".$key;
    }


    public function generateTVVendApiString($transactionID, $smartCardNo, $tvNetwork, $serviceCode, $accessToken, $mode = 1) {
        Log::info('generating api string for tv vending');
        $key = env('TEST_PUBLIC_KEY');
        if($mode == 2) {
            $key = env('LIVE_PUBLIC_KEY');
        }
        Log::info(env('VENDOR_CODE')."|".$transactionID."|".$smartCardNo."|".$tvNetwork."|".$serviceCode."|".$accessToken."|".$key);

        return env('VENDOR_CODE')."|".$transactionID."|".$smartCardNo."|".$tvNetwork."|".$serviceCode."|".$accessToken."|".$key;
    }

    public function hashAPIString($apiString, $mode = 1)
    {
        Log::info('Hashing api string for request.');
        $key = env('TEST_PRIVATE_KEY');
        if ($mode == 2) {
            $key = env('LIVE_PRIVATE_KEY');
        }
        Log::info(hash_hmac("sha1", $apiString, $key));

        return hash_hmac("sha1", $apiString, $key);
    }

    public function purifyJSON($json)
    {
        if (substr($json, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $json = substr($json, 3);
        }
        return $json;
    }

    public function getDataBundles($apiID, $networkID, $mode = 1)
    {
        $resp = array(
            'status'    =>  0,
            'msg'       => 'Pending.'
        );
        $vendorURL = env('VENDOR_TEST_URL');
        if ($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        if ($apiID === 1) {
            $respFormat = "json";
            $url = $vendorURL . "/get_data_bundles.php";
            $url .= "?data_network=" . urlencode($networkID) . "&response_format=" . urlencode($respFormat);
            Log::info('requesting bundle for:');
            Log::info($url);
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ));
            $request = curl_exec($ch);

            if (curl_errno($ch)) {
                Log::error('Error while trying to get data.');
                Log::error(curl_errno($ch));
                Log::error(curl_error($ch));

                $resp['status'] = -1;
                $resp['msg'] = curl_error($ch);
            } else {
                if ($request) {
                    $result = json_decode($this->purifyJSON($request), true);
                    Log::info($result);

                    if ($result['status'] != '00') {
                        $resp['status'] = $result['status'];
                        $resp['msg'] = $result['message'];
                    } else {
                        $resp['status'] = 1;
                        $resp['msg'] = $result['bundles'];
                    }
                }
            }
            curl_error($ch);
        }

        return $resp;
    }

    public function getMeterInfo($meterNo, $referenceID, $disco, $hash, $mode = 1)
    {
        $resp = array(
            'status'   =>   0,
            'msg'      =>   'Pending'
        );
        $respFormat = "json";
        $vendorURL = env('VENDOR_TEST_URL');
        if ($mode == 2) {
            $vendorURL = "https://irecharge.com.ng/pwr_api_live/v2";
        }
        $url = $vendorURL . "/get_meter_info.php?";
        $url .= "vendor_code=" . urlencode(env('VENDOR_CODE')) . "&meter=" . urlencode($meterNo) . "&reference_id=" . urlencode($referenceID) . "&disco=" . urlencode($disco) . "&response_format=" . urlencode($respFormat) . "&hash=" . urlencode($hash);
        Log::info('Making api request for meter info with url:');
        Log::info($url);

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $request = curl_exec($ch);
        Log::error('Response Returned on Live');
        Log::error($request);

        if (curl_errno($ch)) {
            Log::error('Error while trying to get meter info.');
            Log::error(curl_errno($ch));
            Log::error(curl_error($ch));

            $resp['status'] = -1;
            $resp['msg'] = curl_error($ch);
        } else {
            if ($request) {
                $result = json_decode($this->purifyJSON($request), true);
                Log::info('Gotten result from iRecharge API');
                Log::info($result);

                if ($result['status'] != '00') {
                    $resp['status'] = $result['status'];
                    $resp['msg'] = $result['message'];
                } else {
                    $resp['status'] = $result['access_token'];
                    $resp['msg'] = $result['customer'];
                }
            }
        }
        curl_close($ch);

        Log::info('cURL result for get meter info');
        Log::info($resp['status']);
        Log::info($resp['msg']);

        return $resp;
    }

    public function getDiscoProvider($state, $selectedDisco, $meterType)
    {
        Log::info('getting disco provider for state: ' . $state . ', disco: ' . $selectedDisco . ' and meter type: ' . $meterType);
        $disco = '';
        if ($state == 'abuja' || $state == 'nassarawa' || $state == 'kogi' || $state == 'niger' || $state == 'fct') {
            if ($meterType === '2') {
                $disco = 'AEDC_Postpaid';
            } else {
                $disco = 'AEDC';
            }
        }
        if ($state == 'kaduna' || $state == 'sokoto' || $state == 'kebbi' || $state == 'zamfara') {
            if ($meterType === '2') {
                $disco = 'Kaduna_Electricity_Disco_Postpaid';
            } else {
                $disco = 'Kaduna_Electricity_Disco';
            }
        }
        if ($state == 'plateau' || $state == 'bauchi' || $state == 'benue' || $state == 'gombe') {
            $disco = 'Jos_Disco';
        }
        if ($state == 'kano' || $state == 'jigawa' || $state == 'katsina') {
            $disco = 'Kano_Electricity_Disco';
        }
        if ($state == 'oyo' || $state == 'ibadan' || $state == 'osun' || $state == 'ogun' || $state == 'kwara') {
            $disco = 'Ibadan_Disco_Prepaid';
        }
        if ($state == 'rivers' || $state == 'cross river' || $state == 'akwa ibom' || $state == 'bayelsa') {
            if ($meterType === '2') {
                $disco = 'PhED_Electricity';
            } else {
                $disco = 'PH_Disco';
            }
        }
        if ($state == 'delta' || $state == 'edo' || $state == 'ekiti' || $state == 'ondo') {
            $disco = 'Benue_Disco';
        }
        if ($state == 'lagos') {
            if ($selectedDisco === '1') {
                Log::info('Disco passed as 1');
                if ($meterType === '2') {
                    $disco = 'Eko_Postpaid';
                } else {
                    $disco = 'Eko_Prepaid';
                }
            }
            if ($selectedDisco === '2') {
                if ($meterType === '2') {
                    $disco = 'Ikeja_Electric_Bill_Payment';
                } else {
                    $disco = 'Ikeja_Token_Purchase';
                }
            }
        }
        return $disco;
    }

    public function getPowerServiceInfo($name)
    {

        Log::info('Service name = ' . $name);
        $service = \App\Models\Service::on('mysql::read')->where('name', $name)->first();

        if (!$service) {
            $service = "404";
        }

        Log::info('Service = ');

        Log::info($service);

        return $service;

    }

    public function checkAPITransactionStatus($apiID, $receiver)
    {
        $resp = array(
            'status'    => 0,
            'msg'       => 'Pending'
        );
        $timeStamp = date('Y-m-d');
        $statuses = \App\Models\ApiRequest::on('mysql::read')->where('api_id', $apiID)->where('receiver', $receiver)->where('request_timestamp', 'LIKE', '%' . $timeStamp . '%')->get();

        $objects = [];
        if (count($statuses) > 0) {
            $resp['status'] = 1;
            foreach ($statuses as $status) {
                if ($status->status === 0) {
                    $objects[] = $status->id;
                }
            }
        } else {
            $objects = 0;
        }
        $resp['msg'] = $objects;
    }

    public function getTVBouquet($apiID, $providerID, $mode = 1)
    {
        $resp = array(
            'status'    =>  0,
            'msg'       => 'Pending.'
        );
        $vendorURL = env('VENDOR_TEST_URL');
        if ($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        if ($apiID === 1) {
            $respFormat = "json";
            $url = $vendorURL . "/get_tv_bouquet.php";
            $url .= "?tv_network=" . urlencode($providerID) . "&response_format=" . urlencode($respFormat);
            Log::info('requesting bundle for:');
            Log::info($url);
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ));
            $request = curl_exec($ch);

            if (curl_errno($ch)) {
                Log::error('Error while trying to get data.');
                Log::error(curl_errno($ch));
                Log::error(curl_error($ch));

                $resp['status'] = -1;
                $resp['msg'] = curl_error($ch);
            } else {
                if ($request) {
                    $result = json_decode($this->purifyJSON($request), true);
                    Log::info($result);

                    if ($result['status'] != '00') {
                        $resp['status'] = $result['status'];
                        $resp['msg'] = $result['message'];
                    } else {
                        $resp['status'] = 1;
                        $resp['msg'] = $result['bundles'];
                    }
                }
            }
            curl_error($ch);
        }

        return $resp;
    }

    public function getTVCardInfo($transactionID, $smartCardNo, $serviceCode, $tvNetwork, $hash, $respFormat, $mode, $tvAmount = null) {
        // 4290975674
        $resp = array(
            'status'    =>  0,
            'msg'       => 'Pending.'
        );
        $vendorURL = env('VENDOR_TEST_URL');
        if($mode == 2) {
            $vendorURL = env('VENDOR_LIVE_URL');
        }
        $url = $vendorURL . '/get_smartcard_info.php';
        if($tvNetwork !== 'StarTimes'){
            $url .= '?vendor_code=' . urlencode(env('VENDOR_CODE')) . '&smartcard_number=' . urlencode($smartCardNo) . '&service_code=' . urlencode($serviceCode) . '&reference_id=' . urlencode($transactionID) . '&tv_network=' . urlencode($tvNetwork) . '&hash=' . urlencode($hash) . '&response_format=' . urlencode($respFormat);
        }else{
            $url .= '?vendor_code=' . urlencode(env('VENDOR_CODE')) . '&smartcard_number=' . urlencode($smartCardNo) . '&service_code=' . urlencode($serviceCode) . '&reference_id=' . urlencode($transactionID) . '&tv_network=' . urlencode($tvNetwork) . '&hash=' . urlencode($hash) . '&response_format=' . urlencode($respFormat).'&tv_amount='.urlencode($tvAmount);
        }
        Log::info('getting tv card info:');
        Log::info($url);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        $request = curl_exec($ch);

        if(curl_errno($ch)) {
            Log::error('Error while trying to get data.');
            Log::error(curl_errno($ch));
            Log::error(curl_error($ch));

            $resp['status'] = -1;
            $resp['msg'] = curl_error($ch);
        } else {
            if($request) {
                $result = json_decode($this->purifyJSON($request), true);
                Log::info('result from verifying card info');
                Log::info($result);

                if($result['status'] != '00'){
                    $resp['status'] = -1;
                    $resp['msg'] = $result;
                }else{
                    $resp['status'] = 1;
                    $resp['msg'] = $result;
                }
            }
        }
        curl_close($ch);
        return $resp;
    }

    // public function getTVCardInfo($transactionID, $smartCardNo, $serviceCode, $tvNetwork, $hash, $respFormat, $mode)
    // {
    //     // 4290975674
    //     $resp = array(
    //         'status'    =>  0,
    //         'msg'       => 'Pending.'
    //     );
    //     $vendorURL = env('VENDOR_TEST_URL');
    //     if ($mode == 2) {
    //         $vendorURL = env('VENDOR_LIVE_URL');
    //     }
    //     $url = $vendorURL . '/get_smartcard_info.php';
    //     // $url .= '?vendor_code=' . urlencode(env('VENDOR_CODE')) . '&smartcard_number=' . urlencode($smartCardNo) . '&service_code=' . urlencode($serviceCode) . '&reference_id=' . urlencode($transactionID) . '&tv_network=' . urlencode($tvNetwork) . '&hash=' . urlencode($hash) . '&response_format=' . urlencode($respFormat);



    //     $url .= "?vendor_code=" . urlencode(env('VENDOR_CODE')) . "&response_format=" . urlencode($respFormat) . "&hash=" . urlencode($hash) . "&reference_id=" . urlencode($transactionID) . "&service_code=" . urlencode($serviceCode) . "&tv_network=" . urlencode($tvNetwork) . "&smartcard_number=" . urlencode($smartCardNo);

    //     // return [urlencode(env('VENDOR_CODE')), urlencode($respFormat), urlencode($hash), urlencode($transactionID), urlencode($serviceCode), urlencode($tvNetwork), urlencode($smartCardNo)];

    //     Log::info('getting tv card info:');
    //     Log::info($url);
    //     $ch = curl_init();
    //     curl_setopt_array($ch, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => 1,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //         CURLOPT_SSL_VERIFYHOST => 0,
    //         CURLOPT_SSL_VERIFYPEER => 0
    //     ));
    //     $request = curl_exec($ch);

    //     if (curl_errno($ch)) {
    //         Log::error('Error while trying to get data.');
    //         Log::error(curl_errno($ch));
    //         Log::error(curl_error($ch));

    //         $resp['status'] = -1;
    //         $resp['msg'] = curl_error($ch);
    //     } else {
    //         if ($request) {
    //             $result = json_decode($this->purifyJSON($request), true);
    //             Log::info('result from verifying card info');
    //             Log::info($result);

    //             $resp['status'] = 1;
    //             $resp['msg'] = $result;
    //         }
    //     }
    //     curl_close($ch);
    //     return $resp;
    // }

    public function generateRandomUserID()
    {
        $digits_needed = 12;
        $random_number = ''; // set up a blank string
        $count = 0;
        while ($count < $digits_needed) {
            $random_digit = mt_rand(0, 13);
            $random_number .= $random_digit;
            $count++;
        }
        return $random_number;
    }

    public function sendSMS($message, $phone)
    {
        $curl = curl_init();
        $base_url = "http://login.betasms.com.ng/api/?";
        $username = "care@sundiatapost.com";
        $password = "Sundiata123";

        $message = str_replace("\n", " ", $message);
        $message = str_replace(" ", "%20", $message);

        $api_call = $base_url . "username=" . $username . "&password=" . $password . "&sender=" . $sender . "&mobiles=" . $phone . "&message=" . $message;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_call,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 906571e4-3767-097f-fad8-3524f771e188"
            ),
        ));

        $response = curl_exec($curl);
        Log::info("Response from beta sms");
        Log::info($response);
        $err = curl_error($curl);

        curl_close($curl);
    }

    public function getStates()
    {
        return response()->json(\App\Models\State::all());
    }

    public function getLga()
    {
        return response()->json(\App\Models\LGA::all());
    }

    public function getService($typeID)
    {
        return response()->json(\App\Models\Service::on('mysql::read')->where('service_type_id', $typeID)->get());
    }


    //Vending Power
    public function processPowerTransaction($powerTransaction, $amountPaid, $token){

        // $referenceID, $receiver, $disco, $amount, $accessToken, 45700209468
        $serviceName = \App\Models\Service::on('mysql::read')->find($powerTransaction->service_id);
        $powerString = $this->generatePowerVendAPIString(substr($powerTransaction->transaction_id, -12), $powerTransaction->meter_num, $serviceName->name, intval($powerTransaction->amount), $powerTransaction->access_token, env('MODE'));
        $powerHash = $this->hashAPIString($powerString, env('MODE'));

        /* if($powerTransaction->payment_method == "WALLET") {
            $user = \App\Models\User::find($powerTransaction->user_id);
            $current_balance = $user->wallet->balance;
            $new_balance = $current_balance - intval($powerTransaction->amount + $serviceName->service_charge);
            $user->wallet()->update(['balance' => $new_balance]);
            $description = $serviceName->name . ' N ' . $powerTransaction->amount_paid . ' to ' . $powerTransaction->meter_num;
            app('App\Http\Controllers\WalletController')->logWalletTransaction($user, $powerTransaction->amount, $new_balance, 2, $description, 1, $powerTransaction->transaction_id);
            $powerTransaction->update(['amount_paid' => ($powerTransaction->amount + $serviceName->service_charge)]);
        } */

        $vendResult = $this->vendPower($powerTransaction->meter_num, substr($powerTransaction->transaction_id, -12), $serviceName->name, $powerTransaction->access_token, intval($powerTransaction->amount), $powerTransaction->phone, $powerTransaction->email, $powerHash);

    }

    public function vendPower($meterNo, $referenceID, $disco, $accessToken, $amount, $phone, $email, $hash) {
        try{
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
        /* Http::post('https://hooks.slack.com/services/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',[
            'text' => $request.'========'.$url,
            'username' => 'Power bill payment',
            'icon_emoji' => ':zap:'
        ]); */
        if(curl_errno($ch)) {
            Log::info('cURL error occured while trying to dispense airtime.');
            Log::error(curl_error($ch));

            $status['status'] = curl_errno($ch);
            $status['msg'] = curl_error($ch);

            $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
            $powerTransaction->update([
                'status' => 3
            ]);
            $apiID = $this->resolveServiceNameFromID($powerTransaction->service_id, 1);
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
                $result = json_decode($this->purifyJSON($request), true);
                Log::info('Gotten result from irecharge api');
                Log::info($result);

                if($result['status'] == '00') {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
                    $powerTransaction->update([
                        'token' =>  $result['meter_token'],
                        'units' =>  $result['units'],
                        'status' => 2,
                        'customer_address'=> $result['address'],
                    ]);
                    // also update api requests table.
                    $apiID = $this->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = $this->checkAPITransactionStatus($apiID, $meterNo);
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
                        $this->sendSMS($sms, $phone);
                    }
                } else {
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
                    $powerTransaction->update([
                        'units' =>  $result['status'],
                        'status' => 3
                    ]);

                    $apiID = $this->resolveServiceNameFromID($powerTransaction->service_id, 1);
                    // check if there's a record for this transaction on the api requests table.
                    $apiRequestStatus = $this->checkAPITransactionStatus($apiID, $meterNo);
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
                $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $accessToken)->first();
                $powerTransaction->update([
                    'status' => 3
                ]);
            }
        }
        curl_close($ch);
    }catch(Exception $e){
        Http::post('https://hooks.slack.com/services/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',[
            'text' => $e->getMessage(),
            'username' => 'UtilityController - vend power method (api.transave.com.ng) ',
            'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
        ]);

        return response()->json(['message'=>$e->getMessage()], 422);
    }
    }
}
