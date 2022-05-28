<?php

namespace App\Classes;

use App\Enums\ActivityType;
use App\Enums\TransactionType;
use App\Jobs\CreditEmailJob;
use App\Jobs\PosPurchaseJob;
use App\Mail\CreditEmail;
use App\Mail\DebitEmail;
use App\Mail\TransactionMail;
use App\Models\AccountNumber;
use App\Models\Models\CallbackVerification;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ManagesTransactions;
use App\Traits\SendSms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class BankRegistrationHook {

    public string $url;
    public string $access_token;
    public string $wallet_id;
    public string $hook_url;

    use SendSms, ManagesTransactions;

    public function __construct()
    {
        $this->url = config('vfd.url');
        $this->access_token = config('vfd.key');
        $this->wallet_id = config('vfd.wallet_id');
        $this->hook_url = config('vfd.hook_url');
    }

    public function registerUserUsingBvn($request)
    {
        try{
            // return 12;

            $validator = Validator::make($request->all(),[
                'bvn' => 'required ',
                'wallet_id' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            if(env('APP_ENV') === 'local'){
                $bvn = "22222222234";
                $dob = "07-Aug-1958";
            }

            if(env('APP_ENV') === 'production'){
                $bvn = $request->input('bvn');
                $dob = $request->input('dob');
                $wallet_id = $request->input('wallet_id');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->access_token
            ])->post($this->url.'client/create?bvn='.$bvn.'&dateOfBirth='.$dob.'&wallet-credentials='.$this->wallet_id,[
                'payload' => 'string',
            ]);

            if($response->getStatusCode() === 200){
                //Send Email
                AccountNumber::on('mysql::write')->create([
                    'account_number' => $response['data']['accountNo'],
                    'account_name' => 'VFD Microfinance Bank Limited',
                    'wallet_id' => $wallet_id
                ]);
                return $response['data']['accountNo'];
            }

        } catch(\Exception $e) {
            Http::post($this->hook_url,[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook - (api.transave.com.ng) ',
                'icon_emoji' => ':boom:'
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    public function callbackHook($request)
    {
        $verifyAccount = AccountNumber::on('mysql::read')->where('account_number','like','%'.$request->account_number.'%')->first();
        if(!empty($verifyAccount)){
            $updateWalletBalance = Wallet::on('mysql::write')->find($verifyAccount->wallet_id);
            $updateWalletBalance->update([
                'balance' => $updateWalletBalance->balance + $request->amount
            ]);
//            if($updateWalletBalance){
                //Send Email
                $user = User::on('mysql::read')->find($updateWalletBalance->user_id);
                $transaction = WalletTransaction::on('mysql::write')->create([
                    'wallet_id' => $verifyAccount->wallet_id,
                    'amount' => $request->amount,
                    'type' => 'Credit',
                    'sender_name' => $request->originator_account_name,
                    'sender_account_number' => $request->originator_account_number,
                    'reference' => $request->reference
                ]);
                $acct = $request->account_number;

                dispatch(new PosPurchaseJob($updateWalletBalance));

                dispatch(new CreditEmailJob($user->email,$user->name,$request->amount,$transaction,$updateWalletBalance));

                $message = 'Your account '. substr($acct,0,3).'XXXX'.substr($acct,-3)." Has Been Credited with NGN".number_format($request->amount).'.00 On '.date('d-m-Y H:i:s',strtotime($transaction->created_at)).' By ' .substr($transaction->sender_name,7).". Bal: ".number_format($user->wallet->balance);
                $this->sendSms($user->phone,$message);
//            }
            return response()->json(['message'=> 'Successfully logged']);
        }
    }

    public function registerUserWithoutBvn($request,$walletId)
    {
        try{
            $firstname = '';
            $lastname = '';

            $name = explode(' ',$request->name);
            if(count($name) > 1) {
                $firstname = explode(' ',$request->name)[0];
                $lastname = explode(' ',$request->name)[1];
            } else {
                $firstname = $request->name;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->access_token
            ])->post($this->url.'clientdetails/create?wallet-credentials='.$this->wallet_id,[
                'firstname' => $firstname,
                'lastname' => $lastname,
                'phone' => $request->phone,
            ]);


            if($response['status'] == 00){
                //Send Email
                AccountNumber::on('mysql::write')->create([
                    'account_number' => $response['data']['accountNo'],
                    'account_name' => 'VFD Microfinance Bank Limited',
                    'wallet_id' => $walletId
                ]);

                return ['status' => 200];
            }
            if($response['status'] == 99) {
                Http::post($this->hook_url,[
                    'text' => 'Registration failed - contact VDF ADMIN',
                    'username' => 'BankRegistrationHook - ( '.config('app.url').')',
                    'icon_emoji' => ':boom:'
                ]);
                return ['errors' => 'Registration failed - contact VDF ADMIN','status' => 500];
            }


        } catch(\Exception $e) {
            Http::post( $this->hook_url, [
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook - (api.transave.com.ng) ',
                'icon_emoji' => ':boom:'
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    public function updateUserInfo($request)
    {
        try{
            $user = User::on('mysql::read')->where('id',$request->id)->first();

            if(!empty($user)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->access_token
                ])->post($this->url.'clientdetails/create?wallet-credentials='.$this->wallet_id,[
                    'phone' => $request->phone,
                    'bvn' => $request->bvn,
                ]);
                if($response['status'] == 00){
                    return response()->json(['message' => 'successfully updated']);
                }
            }

            if($response['status'] == 99) {
                Http::post($this->hook_url,[
                    'text' => 'Registration failed - contact VDF ADMIN',
                    'username' => 'BankRegistrationHook - (api.transave.com.ng) ',
                    'icon_emoji' => ':boom:'
                ]);
                return response()->json(['errors' => 'Update failed - contact VDF ADMIN']);
            }


        } catch(\Exception $e) {
            Http::post($this->hook_url,[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook - (api.transave.com.ng) ',
                'icon_emoji' => ':boom:'
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    public function bankTransfer($request)
    {
        try{
            $user_id = Auth::id();
            $pin = $request->input('pin');
            $amount = $request->input('amount');
            $recipient_account = $request->input('recipient_account');
            // $sender_bvn = $request->input('sender_bvn') ? $request->input('sender_bvn') : '22222252234';
            $narration = $request->input('narration') ? $request->input('narration') : 'narration';
            $bank_code = $request->input('bank_code');
            $bank_name = $request->input('bank_name');
            $account_name = $request->input('receiver_account_name');
            $transfer_type = $bank_code === 999999 ? 'intra' : 'inter';
            $validator = Validator::make($request->all(), [
                'bank_code' => 'required|numeric|digits:6',
                'bank_name' => 'required',
                'recipient_account' => 'required|numeric|digits:10',
                'amount' => 'required|numeric|max:3000000',
                'pin' => 'required|digits:4',
                'receiver_account_name' => 'required'
            ]);
            $key = $this->handleCacheKeys(__CLASS__.__FUNCTION__."$user_id:$bank_name:$amount");

            $data = [
                'user_id' => $user_id,
                'recipient_account' => $recipient_account,
                'bank_Code' => $bank_code,
                'bank_name' => $bank_name,
                'pin' => $pin,
                'account_name' => $account_name,
                'created_time' => now()
            ];

            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            $idempotent = Cache::store('file')->get($key);

            //Make transaction Idempotent
            if(!empty($idempotent)) {
                return response()->json(['message' => 'Your request is currently being processed.'], 201);
            }
            $this->createIdempotent($key,$data);

            $account_verification = $this->verifyUserByBVN($transfer_type,$recipient_account,$bank_code);
            if($account_verification['status'] == 00){
                 $user = User::on('mysql::read')->where('id',$user_id)->first();
                 $transaction_pin = $user ? $user->transaction_pin : 0;
                if(Hash::check(trim($pin),$transaction_pin)){
                    $verifyAccountBalance = Wallet::on('mysql::write')->where('user_id',$user_id)->first();
                    if($verifyAccountBalance->balance >= $amount){
                        $balance = $verifyAccountBalance->balance - $amount;

                        $succesfulTransfer = $this->makeTransaction($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code);
                        if($succesfulTransfer['status'] == 00){
                            $verifyAccountBalance->update([
                                'balance' => $balance
                            ]);

                            // Send Email
                            $wallet_transaction = WalletTransaction::on('mysql::write')->create([
                                'wallet_id' => $verifyAccountBalance->id,
                                'amount' => $amount,
                                'type' => 'Debit',
                                'reference' => $succesfulTransfer['data']['txnId'],
//                                'sender_account_number' => $recipient_account,
                                'receiver_account_number' => $recipient_account,
                                'receiver_name' =>  $account_name,
                                'bank_name' => $bank_name,
                                'transfer' => true,
                            ]);
                            $account = $verifyAccountBalance->account_numbers->where('account_name','VFD Microfinance Bank Limited')->first();
                            if(!empty($account)){
                                Mail::to($user->email)
                                    ->send(new DebitEmail($user->name,$request->amount,$wallet_transaction,$verifyAccountBalance,$account));
                                $message = 'Your account '. substr($account->account_number,0,3).'XXXX'.substr($recipient_account,-3)." Has Been Debited with NGN".number_format($request->amount).'.00 On '.date('d-m-Y H:i:s',strtotime($wallet_transaction->created_at)).'. Bal: '.number_format($user->wallet->balance);
                                $this->sendSms($user->phone,$message);
                                Http::post(env('VFD_HOOK_URL'),[
                                    'text' => "$user->name($user->phone) sent $amount to $account_name( $recipient_account - $bank_name)",
                                    'username' => 'Bank Transaction Controller',
                                    'icon_emoji' => ':boom:',
                                    'channel' => env('SLACK_CHANNEL_T')
                                ]);
                                //save transaction
                                $transaction = $this->createTransaction($amount, ActivityType::BANK_TRANSFER, TransactionType::DEBIT);
                                $data['bank'] = $request->bank_name;
                                $data['reference'] = $succesfulTransfer['data']['txnId'];
                                $this->createBankTransaction($data, $transaction);

                                return response()->json(['message'=> 'Transaction successfully','txnId' => $succesfulTransfer['data']['txnId']], 201);
                            }
                            return response()->json(['message'=> 'Transaction successfully sent but notification not sent','txnId' => $succesfulTransfer['data']['txnId']], 201);
                        } else {
                            return response()->json(['message'=> 'Failed Transaction','txnId' => $succesfulTransfer['data']['txnId']], 403);
                        }

                    } else {
                        return response()->json(['message'=>'Insufficient Balance.'], 420);
                    }
                }else {
                    return response()->json(['message'=>'Wrong pin, You\'ve 3 more attempt.'], 403);
                }

            }else {
                return response()->json([
                    'message' => $account_verification['message'],
                ],404);
            }

        } catch(\Exception $e) {
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook Class - (BANK Transfer)',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    private function makeTransaction($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code)
    {
        return $this->processBankTransfer($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code);
    }

    private function verifyUserByBVN($transfer_type,$recipient_account,$bank_code)
    {
        try{
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->access_token
            ])->get($this->url.'transfer/recipient?transfer_type='.$transfer_type.'&accountNo='.$recipient_account.'&bank='.$bank_code.'&wallet-credentials='.$this->wallet_id);
            return $response;
        } catch(\Exception $e) {
            Http::post($this->hook_url,[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook Class - (VERIFY USER ACCOUNT)',
                'icon_emoji' => ':boom:'
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    private function createIdempotent($key,$data)
    {
        Cache::store('file')->put($key,$data,150);
    }

    function handleCacheKeys(string $key)
    {
        if (str_contains($key, '-')) {
            $finalKey =  str_replace(
                '-',
                '_',
                $key
            );
            return $finalKey;

        }
        else{
            return $key;
        }
    }
}
