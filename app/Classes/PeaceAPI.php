<?php

namespace App\Classes;

use App\Enums\ActivityType;
use App\Enums\TransactionType;
use App\Models\AccountNumber;
use App\Traits\ManagesTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class PeaceAPI
{
    protected $baseUrl;
    protected $token;
    public $account_number;

    use ManagesTransactions;

    public function __construct()
    {
        $this->baseUrl = env('PEACE_URL');
        $this->token = env('PEACE_TOKEN');
        $this->account_number = new AccountNumber();
    }


    public function code()
    {
        //
    }

    public function clientSavingsAccount($user,$data)
    {
        //For business acount we need productCode - 202
        $bvn = $user->bvn;
        $lname = $data->last_name;
        $Oname = "$data->first_name $data->middle_name";
        $phone = $user->phone;
        $gender = $user->sex;
        $placeOfBirth = $data->place_of_birth ? $data->place_of_birth : '2021-04-11';
        $dateOfBirth = $user->dob ? $user->dob : '2021-04-11';
        $address = $data->address;
        $email = $user->email;

        $params = $this->accountCreationParams($bvn,$lname,$Oname,$phone,$gender,$placeOfBirth,$dateOfBirth,$address,$email);
        $response = Http::post($params['url'],$params['params']);

        if($response['IsSuccessful'] && isset($response['Message']['AccountNumber'])){
            $this->logAccountCreation($response,$user);
        } elseif ($response['IsSuccessful'] && !isset($response['Message']['AccountNumber'])){
            $response = Http::post($params['url'],$params['params']);
            $this->logAccountCreation($response,$user);
        } else {
            Http::post(env('VFD_HOOK_URL'),[
                'text' => "$user->name($user->phone) could not have an account on Peace",
                'username' => 'Peace Account Creation Failed',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL')
            ]);
//            throw new \Exception('Couldnt create account on peace');
        }
    }

    private function logAccountCreation($response,$user)
    {
        $this->account_number->updateOrCreate([
            'account_number' => $response['Message']['AccountNumber'],
            'account_name' => 'Peace Micro Finance Bank'
        ],[
            'account_number' => $response['Message']['AccountNumber'],
            'account_name' => 'Peace Micro Finance Bank',
            'wallet_id' => $user->wallet->id
        ]);
        Http::post(env('VFD_HOOK_URL'),[
            'text' => "$user->name($user->phone) has an account on PEACE ",
            'username' => 'Peace Account Creation Controller',
            'icon_emoji' => ':boom:',
            'channel' => env('SLACK_CHANNEL')
        ]);
    }


    public function clientBusinessAccount($user,$data)
    {
        //For business acount we need productCode - 202
        $bvn = $user->bvn;
        $lname = $data->last_name;
        $Oname = "$data->first_name $data->middle_name";
        $phone = $user->phone;
        $gender = $user->sex;
        $placeOfBirth = $data->place_of_birth ? $data->place_of_birth : '2021-04-11';
        $dateOfBirth = $user->dob ? $user->dob : '2021-04-11';
        $address = $data->address;
        $email = $user->email;

        $params = $this->accountCreationParams($bvn,$lname,$Oname,$phone,$gender,$placeOfBirth,$dateOfBirth,$address,$email);

        $response = Http::post($params['url'],$params['params']);
        if($response['IsSuccessful'] && isset($response['Message']['AccountNumber'])){
            $this->logAccountCreation($response,$user);
        } elseif ($response['IsSuccessful'] && !isset($response['Message']['AccountNumber'])){
            $response = Http::post($params['url'],$params['params']);
            $this->logAccountCreation($response,$user);
        } else {
            Http::post(env('VFD_HOOK_URL'),[
                'text' => "$user->name($user->phone) could not have an account on Peace",
                'username' => 'Peace Account Creation Failed',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL')
            ]);
//            throw new \Exception('Couldnt create account on peace');
        }
    }

    private function accountCreationParams($bvn,$lname,$Oname,$phone,$gender,$placeOfBirth,$dateOfBirth,$address,$email)
    {
        $url = env('PEACE_URL_BANKONE').'Account/CreateCustomerAndAccount/2?authtoken='.env('PEACE_TOKEN');
        $params = [
            'BVN' => $bvn,
            'TransactionTrackingRef' => substr(uniqid('TR'),0,10).'3R',
            'AccountOfficerCode' => "tranSa32",
            'AccountOpeningTrackingRef' => substr(uniqid('AC'),0,10).'0A',
            'ProductCode' => 100,
            'LastName' => $lname,
            'OtherNames' => $Oname,
            'FullName' => $lname.$Oname,
            'PhoneNo' => $phone,
            'Gender' => $gender,
            'PlaceOfBirth' => $placeOfBirth,
            'DateOfBirth' => $dateOfBirth,
            'Address' => $address,
            'NationalIdentityNo' => 0,
            'AccountInformationSource' => 0,
            'Email' => $email,
            'NotificationPreference' => 0,
            'TransactionPermission' => 0
        ];

        return [
            'url' => $url,
            'params' => $params
        ];
    }

    public function localTransfer($request)
    {
        $bvn = $request->input('amount');
        $url = "CoreTransactions/LocalFundsTransfer";
        $params = [
            'amount' => $bvn,
            'fromAccountNumber' => $bvn,
            'toAccountNumber' => $bvn,
            'retrievalReference' => $bvn,
            'narration' => $bvn,
        ];
        //save transaction
        $transaction = $this->createTransaction($request->amount, ActivityType::BANK_TRANSFER, TransactionType::DEBIT);
        $data['bank'] = $bvn;
        $this->createBankTransaction($data, $transaction);

        $response = $this->urlPostControl($url,$params);
    }

    public function fetchBankCode()
    {
        try {
            $url = "BillsPayment/GetCommercialBanks/";
            $response = $this->urlGetControl($url);
            return response()->json(['data' => $response->json()],201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function interTransfer($request)
    {
        $bvn = $request->input('amount');
        $url = "Transfer/InterbankTransfer";
        $params = [
            'amount' => $bvn,
            'payerAccountNumber' => $bvn,
            'payer' => $bvn,
            'receiverBankCode' => $bvn,
            'ReceiverAccountNumber' => $bvn,
            'ReceiverName' => $bvn,
            'ReceiverPhoneNumber' => $bvn,
            'ReceiverAccountType' => $bvn,
            'ReceiverKYC' => $bvn,
            'ReceiverBVN' => $bvn,
            'TransactionReference' => $bvn,
            'Narration' => $bvn,
            'NIPSessionID' => $bvn,
        ];
        $response = $this->urlPostControl($url, $params);
    }

    public function bvnVerification($request)
    {
        $bvn = $request->input('bvn');
        $url = 'Account/BVN/GetBVNDetails';
        $type = 'POST';
        $params = [
            'bvn' => $bvn,
        ];
        $response = $this->urlPostControl($url,$params,$type);

        if($response['isBvnValid']){
            return response()->json(['message' => 'Bvn successfully verified','verification'=>$response->json()],201);
        }

        return response()->json(['message' => 'Could not verify bvn, please ensure your bvn is correct','verification'=>$response->json()],403);
    }

    public function nameEnquiry($request)
    {
        $account = $request->input('account_number');
        $bankCode = $request->input('bank_code');
        $url = "Transfer/NameEnquiry";
        $params = [
            'AccountNumber' => $account,
            'BankCode' => $bankCode,
        ];
        $response = $this->urlPostControl($url,$params);

        return response()->json(['data' => $response->json()],201);
    }

    public function verifyTransactionStatus(Request $request)
    {
        $reference = $request->input('RetrievalReference');
        $date = $request->input('TransactionDate');
        $type = $request->input('TransactionType');
        $amount = $request->input('Amount');
        $url = "CoreTransactions/TransactionStatusQuery";

        $params = [
            'RetrievalReference' => $reference,
            'TransactionDate' => $date,
            'TransactionType' => $type,
            'Amount' => $amount,
        ];
        return $response = $this->urlPostControl($url,$params);
    }

    private function urlPostControl($url,$params)
    {
        $token = [
            'token' => $this->token
        ];
        return Http::post($this->baseUrl.$url,array_merge($params,$token));
    }

    private function urlGetControl($url)
    {
        $token = [
            'token' => $this->token
        ];
        return Http::get($this->baseUrl.$url.$token['token']);
    }


}
