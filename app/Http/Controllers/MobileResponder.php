<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Apis\UtilityController;

class MobileResponder extends Controller
{
    public $utility;
    public function __construct(UtilityController $utility) {
        $this->utility = $utility;
    }

    public function failed_transaction(Request $request) {
        return response()->json($request->all());
    }

    public function airtime_requests(Request $request, $locator, $serviceId) {
        $msg                = '';
        $airtimeTransaction = 0;
        $success            = false;
        $is_vendor          = false;
        $txRef = $request->query('txref') ? $request->query('txref') : $request->txref;
        Log::info('supplied txref: ');
        Log::info($txRef);
        $previouslyExists = \App\Models\AirtimeTransaction::on('mysql::read')->where('payment_ref', $txRef)->first();
        if (empty($previouslyExists)) {
            $response = $this->verify_payment($txRef, env('MODE'));
            // return response()->json($response);
            Log::info('payment verification response');
            Log::info($response->raw_body);
            if ($response->code == 200) {
                if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
                    $name = explode(" ", $response->body->data->custname);
                    $data = array(
                        'fname'             => $name[0] ? $name[0] : "Anonymous",
                        'lname'             => $name[1] ? $name[1] : "Customer",
                        'phone'             => $response->body->data->custphone,
                        'email'             => $response->body->data->custemail,
                        'payment_ref'       => $txRef,
                        'amount'            => $response->body->data->amount,
                        'amount_paid'       => $response->body->data->chargedamount,
                        'transaction_id'    => $this->utility->generateTransactionID(1),
                        'status'            => 0,
                        'commission'        => 0,
                        'payment_method'    => 'FLUTTER',
                        'platform'          => 'MOBILE',
                        'user_id'           => $locator,
                        'service_id'        => $serviceId
                    );
                    $userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
                    if($userID !== -1) {
                        // this user already has an account with us.
                        $data['user_id'] = $userID;
                    } else {
                        // this user does not exist so create an account for the user;
                        $userData = array(
                            'fname'     =>  'Anonymous',
                            'lname'     =>  'Customer',
                            'phone'     =>  $data['phone'],
                            'email'     =>  'anonymous' . $data['email'],
                            'password'  =>  $this->utility->generatePassword(),
                            'gLocatorID'    =>  $data['user_id'],
                            'role_id'       =>  1
                        );
                        $user = $this->utility->createUser($userData);
                        $data['user_id'] = $user->id;
                    }
                    $airtimeTransaction = \App\Models\AirtimeTransaction::on('mysql::write')->create($data);

                    event(new \App\Events\AirtimeRequestEvent($airtimeTransaction));
                    $msg            = 'You should receive your airtime shortly with a notification to your e-mail and phone number.';
                    $success        = true;
                } else {
                    $msg = 'Payment Failed';
                    $airtimeTransaction = 0;
                }
            } else {
                $msg            = 'Payment Failed!';
                $airtimeTransaction    = 0;
            }
        } else {
            $msg = 'Tranasaction Previously Fullfilled!';
        }
        return view('mobile.airtime', compact('is_vendor', 'msg', 'airtimeTransaction', 'success'));
    }

    public function power_requests(Request $request, $locator, $token) {
        $status = 0;
        $msg                = '';
        $powerTransaction   = 0;
        $success            = false;
        $is_vendor          = false;
        $powerTransaction = \App\Models\PowerTransaction::on('mysql::write')->where('access_token', $token)->where('status', 0)->first();
        if ($powerTransaction->status == 2) {
            $msg = 'Transaction previously fulfilled!';
        } else {
            $txRef = $request->query('txref') ? $request->query('txref') : $request->txref;
            $response = $this->verify_payment($txRef, env('MODE'));
            Log::info('payment verification response');
            Log::info($response->raw_body);
            if ($response->body->data->status) {
                if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
                    $powerTransaction->update([
                        'status'        =>  1,
                        'payment_ref'   =>  $txRef,
                        'amount_paid'   =>  $response->body->data->chargedamount
                    ]);
                    event(new \App\Events\NewPowerVendEvent($powerTransaction, $powerTransaction->amount, $token));
                    $powerTransaction = \App\Models\PowerTransaction::on('mysql::read')->find($powerTransaction->id);
                    if($powerTransaction->status != 2) {
                        // something must have gone wrong while trying to dispense.
                        $status = -2000;
                        $msg = 'Failed.';
                    } else {
                        $status = 2000;
                        $success = true;
                        $msg = array('token' => $powerTransaction->token, 'units' => $powerTransaction->units, 'amountPaid' => $powerTransaction->amount_paid, 'transID' => $powerTransaction->transaction_id);
                    }
                }
            } else {
                $msg = 'Payment Failed!';
            }
        }
        return view('mobile.power', compact('is_vendor', 'msg', 'powerTransaction', 'success', 'status'));
    }

    public function data_requests(Request $request, $locator, $serviceId, $dataBundleId) {
        $msg                = '';
        $dataTransaction    = 0;
        $success            = false;
        $is_vendor          = false;
        $txRef = $request->query('txref') ? $request->query('txref') : $request->txref;
        $previouslyExists = \App\Models\DataTransaction::on('mysql::read')->where('payment_ref', $txRef)->first();
        if (empty($previouslyExists)) {
            $response = $this->verify_payment($txRef, env('MODE'));
            Log::info('payment verification response');
            Log::info($response->raw_body);
            if ($response->code == 200) {
                if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
                    $name = explode(" ", $response->body->data->custname);
                    $data = array(
                        'fname'             => $name[0] ? $name[0] : "Anonymous",
                        'lname'             => $name[1] ? $name[1] : "Customer",
                        'phone'             => $response->body->data->custphone,
                        'email'             => $response->body->data->custemail,
                        'payment_ref'       => $txRef,
                        'amount'            => $response->body->data->amount,
                        'amount_paid'       => $response->body->data->chargedamount,
                        'transaction_id'    => $this->utility->generateTransactionID(2),
                        'status'            => 0,
                        'commission'        => 0,
                        'payment_method'    => 'FLUTTER',
                        'platform'          => 'MOBILE',
                        'user_id'           => $locator,
                        'service_id'        => $serviceId,
                        'data_bundles_id'   => $dataBundleId,
                    );
                    $userID = $this->utility->getUserByGLocatorID($data['user_id'], $data['email']);
                    if($userID !== -1) {
                        // this user already has an account with us.
                        $data['user_id'] = $userID;
                    } else {
                        // this user does not exist so create an account for the user;
                        $userData = array(
                            'fname'     =>  'Anonymous',
                            'lname'     =>  'Customer',
                            'phone'     =>  $data['phone'],
                            'email'     =>  'anonymous' . $data['email'],
                            'password'  =>  $this->utility->generatePassword(),
                            'gLocatorID'=>  $data['user_id'],
                            'role_id'   =>  1
                        );
                        $user = $this->utility->createUser($userData);
                        $data['user_id'] = $user->id;
                    }
                    $dataPurchase = \App\Models\DataTransaction::on('mysql::write')->create($data);
                    event(new \App\Events\DataRequestEvent($dataPurchase));
                    $msg = 'You should receive your data bundle shortly with a notification to your e-mail and phone number.';
                    $dataTransaction = \App\Models\DataTransaction::on('mysql::read')->find($dataPurchase->id);
                    $success = true;
                }
            } else {
                $msg = "Payment Failed!";
            }
        } else {
            $msg = 'Transaction previously fulfilled!';
        }
        return view('mobile.data', compact('is_vendor', 'msg', 'dataTransaction', 'success', 'status'));
    }

    public function tv_requests(Request $request, $locator, $token) {
        $status = null;
        $msg                = '';
        $tvTransaction      = 0;
        $success            = false;
        $is_vendor          = false;
        $tvTransaction = \App\Models\TVTransaction::on('mysql::write')->where('access_token', $token)->first();
        if ($tvTransaction->status == 2) {
            $msg = 'Transaction previously fulfilled!';
        } else {
            $txRef = $request->query('txref') ? $request->query('txref') : $request->txref;
            $response = $this->verify_payment($txRef, env('MODE'));
            Log::info('payment verification response');
            Log::info($response->raw_body);
            if ($response->code == 200) {
                $tvTransaction->update([
                    'status'            =>  1,
                    'payment_ref'       => $paymentReference,
                    'amount_paid'       =>  $amountPaid,
                    'payment_method'    =>  'FLUTTER',
                ]);

                event(new \App\Events\NewTVVendEvent($tvTransaction));
                $tvTransaction = \App\Models\TVTransaction::on('mysql::read')->where('access_token', $token)->first();
                if($tvTransaction->status != 2) {
                    // something must have gone wrong while trying to dispense.
                    $status = -2000;
                    $msg ='Failed.';
                } else {
                    $status = 2000;
                    $success = true;
                    $msg = 'success';
                }
            } else {
                $msg = "Payment Failed!";
            }
        }
        return view('mobile.tv', compact('is_vendor', 'msg', 'tvTransaction', 'success', 'status'));
    }

    public function verify_payment($txref, $mode = 1) {
        $data = array(
            'txref' => $txref,
            'SECKEY' => env('FLUTTER_WAVE_SANDBOX_SECRET_KEY')
        );

        $url = "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify";

        if($mode == 2 || $mode == '2') {
            $url = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify";
            $data['SECKEY'] = env('FLUTTER_WAVE_LIVE_SECRET_KEY');
        }

        \Unirest\Request::verifyPeer(false);
        $headers = array('Content-Type' => 'application/json');
        $body = \Unirest\Request\Body::json($data);
        // Make `POST` request and handle response with unirest
        try {
            $response = \Unirest\Request::post($url, $headers, $body);
            Log::info('unirest response code');
            Log::info($response->code);
            return $response;
        } catch(\Exception $ex) {
            Log::info('error trying to verify payment...');
            Log::info($ex->getMessage());
            $resp = $ex->getCode();
            return null;
        }

        // if ($response->body->data->status === "successful" && $response->body->data->chargecode === "00") {
        //     //confirm that the amount is the amount you wanted to charge
        //     if ($response->body->data->amount === $amount) {
        //         $resp = 100;
        //     } else {
        //         return -2;
        //     }
        // } else {
        //     $resp = 404;
        // }
    }
}
