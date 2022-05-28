<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Enums\TransactionType;
use App\Models\AccountNumber;
use App\Models\AirtimeTransaction;
use App\Models\BankTransfer;
use App\Traits\ManagesTransactions;
use App\Traits\ManagesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Business;
use App\Models\DataTransaction;
use App\Models\PowerTransaction;
use App\Models\TVTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletTransfer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Functions;
use App\Mail\DebitEmail;
use App\Mail\TransactionMail;
use App\Models\Beneficiaries;
use App\Models\BusinessCardRequest;
use App\Models\BusinessKyc;
use App\Models\BusinessRoles;
use App\Models\BusinessStaff;
use App\Models\PaystackRefRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class BusinessController extends Controller
{
    use ManagesUsers, ManagesTransactions;
    protected $utility;
    protected $jwt;

    function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->utility = new Functions();
        $this->middleware('limit');
    }

    public function register(Request $request){

        //return $request;
        try{

            $request->validate([
                'user_id'=>'required',
                'name'=>'required|unique:businesses|min:3|string',
                'location'=>'required',
                'country'=>'required',
                'state'=>'required',
                'city'=>'required',
                'category'=>'required'
            ]);
            //return 45676;
        }catch(ValidationException $e){
            return response()->json(['message'=>$e->getMessage(), 'errors'=>$e->errors()], 422);
        }

        //return $request;

        $accNum = substr(uniqid(mt_rand(), true), 0, 10);

        //return $accNum;
        if (!$this->ownsRecord($request->get('user_id'))) {
            return response()->json(['message'=>'You dont have permission to do this operation',], 405);
        }

        $this->saveUserActivity(ActivityType::REGISTER, '', $request->user_id);

        try{
            $user = User::on('mysql::read')->findOrFail($request->user_id);

            $business = Business::on('mysql::write')->create([
                'user_id'=>$request->user_id,
                'name'=>$request->name,
                'location'=>$request->location,
                'country'=>$request->country,
                'state'=>$request->state,
                'city'=>$request->city,
                'category'=>$request->category,
            ]);

            $wallet = $business->wallet()->save(new Wallet());

            $accNumber = AccountNumber::on('mysql::write')->create([
                'account_number'=>$accNum,
                'wallet_id'=>$wallet->id,
                'account_name'=>'Transave Account',
            ]);
            //return $wallet;
            //$wallet->account_number = $accNum;
            //$wallet->save();
            return response()->json(['message'=>'Business Registered', 'acccount'=>$business, 'wallet'=>$business->wallet], 200);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'User not found'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function listUserBusinesses($user_id){

        try{
            $user = User::on('mysql::read')->findOrFail($user_id);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'User not found.'], 404);
        }

        $businesses = $user->businesses;

        if(!$businesses){
            return response()->json(['message'=>'Could not fetch users businesses.'], 404);
        }

        if(sizeof($businesses) == 0){
            return response()->json(['message'=>'User has not registered any Business.'], 420);
        }

        return response()->json(['businesses'=>$businesses],200);
    }

    public function getBusiness($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Business not found.'], 404);
        }

        $business['wallet'] = $business->wallet;

        return response()->json(['business'=>$business],200);
    }

    public function getTransferHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
            $history = WalletTransaction::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['transfer', true]])->get();

            if(!$history){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Business not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

        return response()->json(['message'=>'Business Transfer history', 'history'=>$history], 200);
    }

    public function getSentTransferHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);

            $sent = WalletTransaction::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['type', 'Debit'], ['transfer', true]])->get();

            if(!$sent){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }

            return response()->json(['message'=>'Business sent transfer history retrieved.', 'history'=>$sent], 200);
        }catch(ModelNotFoundException $em){
            return response()->json(['message'=>'Business not found'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getReceivedTransferHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);

            $sent = WalletTransaction::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['type', 'Credit'], ['transfer', true]])->get();

            if(!$sent){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }

            return response()->json(['message'=>'Business received transfer history retrieved.', 'history'=>$sent], 200);
        }catch(ModelNotFoundException $em){
            return response()->json(['message'=>'Business not found'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getAirtimeHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
            $history = AirtimeTransaction::on('mysql::read')->where('user_id', $business_id)->get();
            if(!$history){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Business not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

        return response()->json(['message'=>'Business Airtime transaction history', 'history'=>$history], 200);
    }

    public function getDataHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
            $history = DataTransaction::on('mysql::read')->where('user_id', $business->id)->get();
            if(!$history){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Business not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

        return response()->json(['message'=>'Business Data transaction history', 'history'=>$history], 200);
    }

    public function getTVHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
            $history = TVTransaction::on('mysql::read')->where('user_id', $business->id)->get();
            if(!$history){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Business not found.'], 404);
        }
        catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

        return response()->json(['message'=>'Business TV transaction history', 'history'=>$history], 200);
    }

    public function getPowerHistory($business_id){
        try{
            $business = Business::on('mysql::read')->findOrFail($business_id);
            $history = PowerTransaction::on('mysql::read')->where('user_id', $business->id)->get();
            if(!$history){
                return response()->json(['message'=>'Business transfer history not found.'], 404);
            }
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Business not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

        return response()->json(['message'=>'Business Power transaction history', 'history'=>$history], 200);
    }

    public function walletTransfer(Request $request)
    {
        try{
            $request->validate([
                'user_id'=>'required',
                'account_number'=>'required',
                'amount'=>'required|numeric|gt:0',
                'description'=>'required',
                'pin'=>'required',
            ]);

            $userId = $request->user_id;
            $accNum = $request->account_number;
            $desc = $request->description;
            $amount = intval($request->amount) < 0? floatval($request->amount) * -1 : floatval($request->amount);
            $pin = $request->pin;

            if (!$this->ownsRecord($request->get('user_id'))) {
                return response()->json(['message'=>'You dont have permission to do this operation',], 405);
            }

            try{
                $business = Business::on('mysql::write')->findOrFail($userId);
            }catch(ModelNotFoundException $e){
                return response()->json(['message'=>'Business not found.'], 404);
            }

            if(!$business)
            {
                return response()->json(['message'=>'Business not found'], 404);
            }

            //return $business->user;
            try{
                $receiver = AccountNumber::on('mysql::write')->where('account_number', $accNum)->get()->first();
            }catch(ModelNotFoundException $e){
                return response()->json(['message'=>'Receivers Wallet not found.'], 404);
            }

            try{
                $busAcc = AccountNumber::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['account_name', 'Wallet ID']])->get()->first();
            }catch(ModelNotFoundException $e){
                return array('message'=>'Senders Account not found.', 'status'=>404);
            }

            if(!$receiver)
            {
                return response()->json(['message'=>'Receiving account not found. Please check the Account Number and try again.'], 404);
            }

            if($receiver->wallet_id == $business->wallet->id){
                return response()->json(['message'=>'You cannot transfer to your wallet'], 420);
            }

            //return $receiver->wallet;
            $recWall = $receiver->wallet;
            $recAcc = User::on('mysql::read')->find($recWall->user_id);
            $recEmail = $recAcc->email;
            if(!$recAcc){
                $recAcc = Business::on('mysql::read')->find($recWall->user_id);
                $recEmail = $recAcc->user->email;
                if(!$recAcc){
                    return response()->json(['message'=>'Account owner not found. Please check account number.'], 404);
                }
            }

            if($business->wallet->balance < $amount || $amount <= 0){
                return response()->json(['message'=>'Insufficient balance.'], 420);
            }

            $senderNewBal = intval($business->wallet->balance) - $amount;
            $business->wallet()->update(['balance'=>$senderNewBal]);
            $recNewBal = intval($receiver->wallet->balance) + $amount;
            $receiver->wallet()->update(['balance'=>$recNewBal]);
            $wtw = WalletTransfer::on('mysql::write')->create(['sender'=>$business->wallet->id, 'receiver'=>$receiver->wallet->id, 'amount'=>$amount, 'description'=>$desc]);

            $this->saveUserActivity(ActivityType::WALLET_TRANSFER, TransactionType::DEBIT, $request->user_id);
            //Mail::to($business->)
            $transaction = WalletTransaction::on('mysql::write')->create([
                'wallet_id' => $business->wallet->id,
                'amount' => $amount,
                'type' => 'Debit',
                'sender_account_number'=> $busAcc->account_number,
                'sender_name'=>$business->name,
                'receiver_name'=>$recAcc->name,
                'receiver_account_number'=>$receiver->account_number,
                'description'=>$desc,
                'bank_name'=>'Transave',
                'transfer'=>true,
            ]);

            if(empty($business->user->transaction_pin)){
                $transaction->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Transaction Pin not set.'], 422);
            }

            if(!Hash::check($pin, $business->user->transaction_pin))
            {
                $transaction->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Incorrect Pin!'], 404);
            }

            if($business->wallet->balance < $amount || $amount <= 0){
                $transaction->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Insufficient balance.'], 420);
            }

            $senderNewBal = intval($business->wallet->balance) - $amount;
            $business->wallet()->update(['balance'=>$senderNewBal]);
            $recNewBal = intval($receiver->wallet->balance) + $amount;
            $receiver->wallet()->update(['balance'=>$recNewBal]);
            $wtw = WalletTransfer::on('mysql::write')->create(['sender'=>$business->wallet->id, 'receiver'=>$receiver->wallet->id, 'amount'=>$amount, 'description'=>$desc]);
            //Mail::to($business->)
            $transaction->update([
                'status'=>'success',
            ]);
            $recTrans = WalletTransaction::on('mysql::write')->create([
                'wallet_id' => $receiver->wallet->id,
                'amount' => $amount,
                'type' => 'Credit',
                'sender_account_number'=> $busAcc->account_number,
                'sender_name'=>$business->name,
                'receiver_name'=>$recAcc->name,
                'receiver_account_number'=>$receiver->account_number,
                'description'=>$desc,
                'bank_name'=>'Transave',
                'transfer'=>true,
                'status'=>'success'
            ]);
            $transaction = $this->createTransaction($request->get('amount'), ActivityType::WALLET_TRANSFER, TransactionType::DEBIT);
            $data['receiver_wallet_id'] = $recWall->id;
            $data['description'] = $desc;
            $this->createWalletTransaction($data, $transaction);

            Mail::to($business->user->email)->send(new DebitEmail($business->name,$amount, $transaction, $business->wallet, $busAcc));
            Mail::to($recEmail)->send(new TransactionMail($recAcc->name,$amount));
            return response()->json(['message'=>'Transfer Successful.'], 200);
        }catch(validationException $e)
        {
            return response()->json(['message'=>$e->getMessage(), 'errors'=>$e->errors()], 422);
        }catch(Exception $ee){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController Class - Wallet to wallet transfer',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$ee->getMessage()], 422);
        }
    }

    public function fund_business_wallet_card(Request $request)
    {
        //return $this->utility->test();
        try{
            $request->validate([
                'reference'=>'required',
                'business_id'=>'required|uuid',
            ]);
        }catch(ValidationException $exception){
            return response()->json(['errors'=>$exception->errors(), 'message'=>$exception->getMessage()]);
        }
        $paystack_payment_reference = $request->reference;
        //$amount = $request->amount;
        $businessId = $request->business_id;
        //$key = config('app.paystack');
        $msg = '';

        // verify the payment
        $checkRef = PaystackRefRecord::where('ref', $paystack_payment_reference)->first();

        if($checkRef && $checkRef->status == 'success'){
            return response()->json(['message'=>'Already processed this transaction.']);
        }elseif (!$checkRef){
            $checkRef = PaystackRefRecord::create([
                'ref'=>$paystack_payment_reference,
                'status'=>'pending',
            ]);
        }

        $business = Business::on('mysql::write')->where('id', $businessId)->first();

        if(!$business){
            return response()->json(['message'=>'Could not find business.']);
        }
        //return $business->user->email;
         //$reference ='WALLET-'. $this->business->generate_transaction_reference();

        $verification_status = $this->utility->verifyPaystackPayment($paystack_payment_reference);

        $amount = floatval($verification_status['amount'])/100;

        $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['account_name', 'Wallet ID']])->first();
        $walTransaction = WalletTransaction::on('mysql::write')->create([
            'wallet_id'=>$business->wallet->id,
            'type'=>'Credit',
            'amount'=>$amount,
            'description'=>'Deposit',
            'receiver_account_number'=>$acc->account_number,
            'receiver_name'=>$business->name,
            'transfer'=>false,
            'transaction_ref'=> $paystack_payment_reference,
            'transaction_type'=>'card',
        ]);
        if ($verification_status['status'] == -1) {
            // cURL error
            // log as failed transaction
            $walTransaction->update([
                'status'=>'failed',
            ]);
            $msg = 'Paystack payment verification failed to verify wallet funding.';
            $checkRef->update(['status'=>'failed']);
        } else if ($verification_status['status'] == 503) {
            $walTransaction->update([
                'status'=>'failed',
            ]);
            $msg = 'Paystack payment verification was unable to confirm payment.';
            $checkRef->update(['status'=>'failed']);
        } else if ($verification_status['status'] == 404) {
            $walTransaction->update([
                'status'=>'failed',
            ]);
            $msg = 'Unfortunately, transaction reference not found.';
            $checkRef->update(['status'=>'failed']);
        }else if ($verification_status['status'] == 400) {
            $walTransaction->update([
                'status'=>'failed',
            ]);
            $msg = 'Unfortunately, transaction failed.';
            $checkRef->update(['status'=>'failed']);
        } else if ($verification_status['status'] == 100) {
            $msg = 'Paystack payment verification successful.';
            //return $business->wallet->id;
            //$this->business->update_business_wallet_balance(($business->wallet->balance + $amount));
            $newBal = floatval($business->wallet->balance) + floatval($amount);
            $business->wallet()->update(['balance' => $newBal]);
            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['account_name', 'Wallet ID']])->first();
            $walTransaction = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$business->wallet->id,
                'type'=>'Credit',
                'amount'=>$amount,
                'description'=>'Deposit',
                'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$business->name,
                'transfer'=>false,
                ]);
            $this->saveUserActivity(ActivityType::WALLET_TRANSFER, TransactionType::CREDIT, $business->user->id);
            $walTransaction->update([
                'status'=>'success',
            ]);
            $checkRef->update(['status'=>'success']);
            Mail::to($business->user->email)->send(new TransactionMail($business->name,$amount));
        }

        return response()->json(['message'=>$msg]);
    }

    public function fund_business_wallet_transfer(Request $request)
    {
        try{
            $request->validate([
                'reference'=>'required',
                'business_id'=>'required',

            ]);
        }catch(ValidationException $exception){
            return response()->json(['errors'=>$exception->errors(), 'message'=>$exception->getMessage()]);
        }
        $paystack_payment_reference = $request->reference;
        //$amount = $request->amount;
        $businessId = $request->business_id;
        //$key = config('app.paystack');
        $msg = '';

        // verify the payment

        $business = Business::on('mysql::write')->where('id', $businessId)->first();

        if(!$business){
            return response()->json(['message'=>'Could not find business.']);
        }

         //$reference ='WALLET-'. $this->business->generate_transaction_reference();

         $verification_status = $this->utility->verifyMonifyPayment($paystack_payment_reference);

         //return $verification_status;

         $amount = floatval($verification_status['amount']);
        if ($verification_status['status'] == -1) {
            // cURL error
            // log as failed transaction
            $msg = 'Transfer failed to verify wallet funding.';
        } else if ($verification_status['status'] == 503) {
            $msg = 'Transfer was unable to be confirm.';
        } else if ($verification_status['status'] == 404) {
            $msg = 'Unfortunately, transaction reference not found.';
        }else if ($verification_status['status'] == 400) {
            $msg = 'Unfortunately, transaction is pending.';
        } else if ($verification_status['status'] == 100) {
            $msg = 'Transfer verification successful.';
            //return $business->wallet->id;
            //$this->business->update_business_wallet_balance(($business->wallet->balance + $amount));
            $newBal = floatval($business->wallet->balance) + floatval($amount);
            $business->wallet()->update(['balance' => $newBal]);
            $walTransaction = WalletTransaction::on('mysql::write')->create(['wallet_id'=>$business->wallet->id, 'type'=>'Credit', 'amount'=>$amount]);
            $this->saveUserActivity(ActivityType::WALLET_TRANSFER, TransactionType::CREDIT, $business->user->id);
            Mail::to($business->user->email)->send(new TransactionMail($business->name,$amount));
        }

        return response()->json(['message'=>$msg]);
    }

    public function getBusinessStaff($business_id){
        try{

            $staff = Business::on('mysql::read')->find($business_id)->staff;

            return response()->json(['staff'=>$staff]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Get Business staff method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function payStaffSalary(Request $request){
        try{

            $request->validate([
                'business_id'=>'required|uuid',
                'staff_id'=>'required|uuid',
                'amount'=>'required|integer|numeric|gt:0',
                'tax'=>'required|integer|numeric|gt:0',
                'bonus'=>'integer|numeric|gt:0',
            ]);

            $business = Business::on('mysql::write')->findOrFail($request->business_id);

            $busWallet = $business->wallet;
            $total = intval($request->amount + $request->tax + $request->bonus);


            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['account_name', 'Wallet ID']])->first();

            $staff = BusinessStaff::on('mysql::read')->findOrFail($request->staff_id);
            //Business::find($request->business_id)->staff()->where('business_id', $request->business_id)->first();

            $staffUserAcc = User::on('mysql::write')->findOrFail($staff->user_id);

            $useracc = AccountNumber::on('mysql::read')->where([['wallet_id', $staffUserAcc->wallet->id], ['account_name', 'Wallet ID']])->first();

            $businessWT = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$busWallet->id,
                'type'=>'Debit',
                'amount'=>$total,
                'sender_account_number'=> $acc->account_number,
                'sender_name'=>$business->name,
                'receiver_name'=>$staffUserAcc->name,
                'receiver_account_number'=>$useracc->account_number,
                'description'=>'Salary Payment',
                'bank_name'=>'Transave',
                'transfer'=>true,
            ]);

            if($busWallet->balance < $total || $total <= 0){
                $businessWT->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Insufficient funds.'], 420);
            }

            $newUserBal = $staffUserAcc->wallet->balance + $total;
            $newBusBal = $busWallet->balance - $total;
            $staffUserAcc->wallet()->update(['balance'=>$newUserBal]);
            $busWallet->update(['balance'=>$newBusBal]);

            $userWT = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=>$staffUserAcc->wallet->id,
                'type'=>'Credit',
                'amount'=>$total,
                'sender_account_number'=> $acc->account_number,
                'sender_name'=>$business->name,
                'receiver_name'=>$staffUserAcc->name,
                'receiver_account_number'=>$useracc->account_number,
                'description'=>'Salary Payment',
                'bank_name'=>'Transave',
                'transfer'=>true,
                'status'=>'success',
            ]);

            $businessWT->update([
                'status'=>'success',
            ]);

            Mail::to($business->user->email)->send(new DebitEmail($business->name,$total, $businessWT, $busWallet, $acc));
            Mail::to($staffUserAcc->email)->send(new TransactionMail($staffUserAcc->name,$total));

            return response()->json(['message'=>'Payment to staff successful']);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Staff salary payment method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function payMultiStaffSalary(Request $request){
        try{
            $response = array();
            $totalPaid = 0;
            $request->validate([
                'business_id'=>'required|uuid',
                'staff_id'=>'required|array',
            ]);

            $business = Business::on('mysql::write')->findOrFail($request->business_id);
            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $business->wallet->id], ['account_name', 'Wallet ID']])->first();

            $busWallet = $business->wallet;
            foreach($request->staff_id as $staff_id){
                $temp = array();

                $staff = BusinessStaff::on('mysql::read')->findOrFail($staff_id);

                $staffUserAcc = User::on('mysql::write')->findOrFail($staff->user_id);
                $total = intval($staff->salary + $staff->tax + $staff->bonus);
                $useracc = AccountNumber::on('mysql::read')->where([['wallet_id', $staffUserAcc->wallet->id], ['account_name', 'Wallet ID']])->first();

                $businessWT = WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=>$busWallet->id,
                    'type'=>'Debit',
                    'amount'=>$total,
                    'sender_account_number'=> $acc->account_number,
                    'sender_name'=>$business->name,
                    'receiver_name'=>$staffUserAcc->name,
                    'receiver_account_number'=>$useracc->account_number,
                    'description'=>'Salary Payment',
                    'bank_name'=>'Transave',
                    'transfer'=>true,
                ]);

                if($busWallet->balance < $total || $total <= 0){
                    $businessWT->update(['status'=>'failed',]);
                    $temp['staff'] = $staff;
                    $temp['paid'] = false;
                    array_push($response, $temp);
                    continue;
                    //return response()->json(['message'=>'Insufficient funds.'], 420);
                }

                $newUserBal = $staffUserAcc->wallet->balance + $total;
                $newBusBal = $busWallet->balance - $total;
                $staffUserAcc->wallet()->update(['balance'=>$newUserBal]);
                $busWallet->update(['balance'=>$newBusBal]);

                $userWT = WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=>$staffUserAcc->wallet->id,
                    'type'=>'Credit',
                    'amount'=>$total,
                    'sender_account_number'=> $acc->account_number,
                    'sender_name'=>$business->name,
                    'receiver_name'=>$staffUserAcc->name,
                    'receiver_account_number'=>$useracc->account_number,
                    'description'=>'Salary Payment',
                    'bank_name'=>'Transave',
                    'transfer'=>true,
                ]);

                $businessWT->update(['status'=>'success',]);

                Mail::to($business->user->email)->send(new DebitEmail($business->name,$total, $businessWT, $busWallet, $acc));
                Mail::to($staffUserAcc->email)->send(new TransactionMail($staffUserAcc->name,$total));

                $totalPaid = $totalPaid + $total;
                $temp['staff'] = $staff;
                $temp['paid'] = true;
                array_push($response, $temp);

            }

            return response()->json(['response'=>$response, 'total_paid'=>$totalPaid]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Multiple staff salary payment method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function updateStaffInfo(Request $request){
        try{

            $request->validate([
                'staff_id'=>'required|uuid',
                'name'=>'string',
                'address'=>'string',
                'dob'=>'date',
                'role'=>'string',
                'salary'=>'numeric|integer',
                'pay_cycle'=>'string',
            ]);

            $staff = BusinessStaff::on('mysql::write')->findOrFail($request->staff_id);

            $staff->update([
                'name'=>$request->name,
                'address'=>$request->address,
                'dob'=>date('Y-m-d', strtotime($request->dob)),
                'role'=>$request->role,
                'salary'=>$request->salary,
                'pay_cycle'=>$request->pay_cycle,
            ]);

            return response()->json(['message'=>'Successfully Updated staff payment setup', 'staff'=>$staff]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - staff payment setup method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function onPayroll(Request $request){
        try{

            $request->validate([
                'staff_id'=>'required|uuid',
                'on_payroll'=>'required|boolean',
            ]);

            $staff = BusinessStaff::on('mysql::write')->findOrFail($request->staff_id);

            $staff->update([
                'on_payroll'=>$request->on_payroll
            ]);

            $msg = 'Successfully added staff to payroll.';
            if(!$request->on_payroll){
                $msg = 'Successfully removed staff from payroll';
            }

            return response()->json(['message'=>$msg, 'staff'=>$staff]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Put staff on payroll method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function suspendStaff(Request $request){
        try{

            $request->validate([
                'staff_id'=>'required|uuid',
                'suspend'=>'required|boolean',
            ]);

            $staff = BusinessStaff::on('mysql::write')->findOrFail($request->staff_id);

            $staff->update([
                'suspended'=>$request->suspend
            ]);

            $msg = 'Successfully suspended staff.';
            if(!$request->suspend){
                $msg = 'Successfully removed staff from suspension';
            }

            return response()->json(['message'=>$msg, 'staff'=>$staff]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Put staff on suspension method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function deactivateStaff(Request $request){
        try{

            $request->validate([
                'staff_id'=>'required|uuid',
                'deactivate'=>'required|boolean',
            ]);

            $staff = BusinessStaff::on('mysql::write')->findOrFail($request->staff_id);

            $staff->update([
                'deactivated'=>$request->deactivate
            ]);

            $msg = 'Successfully deactivated staff.';
            if(!$request->deactivate){
                $msg = 'Successfully activated staff ';
            }

            return response()->json(['message'=>$msg, 'staff'=>$staff]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Deactivate staff method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function createRole(Request $request){
        try{

            $request->validate([
                'business_id'=>'required|uuid',
                'role'=>'required|string',
            ]);

            $role = BusinessRoles::on('mysql::write')->create([
                'business_id'=>$request->business_id,
                'role'=>$request->role,
            ]);

            return response()->json(['message'=>'Successfully created new role', 'role'=>$role]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Create role for business method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function deleteRole(Request $request){
        try{

            $request->validate([
                'role_id'=>'required|uuid',
            ]);

            $role = BusinessRoles::on('mysql::write')->findOrFail($request->role_id);

            $role->delete();

            return response()->json(['message'=>'Successfully deleted role']);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Delete role for business method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getAllRoles($business_id){
        try{

            $roles = Business::on('mysql::read')->find($business_id)->roles;


            return response()->json(['message'=>'Successfully retrieved roles', 'roles'=>$roles]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Get all roles for business method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function addStaff(Request $request){
        try{

            $request->validate([
                'business_id'=>'required|uuid',
                'phone_numbers'=>'required|array',
            ]);
            $response = array();

            $business = Business::on('mysql::read')->findOrFail($request->business_id);

            $busWallet = $business->wallet;
            foreach($request->phone_numbers as $staff_phone){
                $temp = array();

                $exists = BusinessStaff::on('mysql::read')->where('phone_number', $staff_phone)->first();
                if($exists){
                    $temp['phone_number'] = $staff_phone;
                    $temp['onboarded'] = false;
                    $temp['message'] = 'Staff already exists with this phone number.';

                    array_push($response, $temp);
                    continue;
                }

                $user = User::on('mysql::read')->where('phone', $staff_phone)->first();

                if(!$user){
                    $temp['phone_number'] = $staff_phone;
                    $temp['onboarded'] = false;
                    $temp['message'] = 'Unable to find user with this phone number.';

                    array_push($response, $temp);
                    continue;
                }

                $staff = BusinessStaff::on('mysql::write')->create([
                    'business_id'=>$request->business_id,
                    'user_id'=>$user->id,
                    'name'=>'',
                    'role'=>'Staff',
                    'salary'=>0,
                    'tax'=>0,
                    'bonus'=>0,
                    'on_payroll'=>false,
                    'suspended'=>false,
                    'deactivated'=>false,
                    'pay_cycle'=>'monthly',
                    'address'=>'',
                    'dob'=>date('Y-m-d'),
                    'phone_number'=>$staff_phone,
                ]);

                $temp['phone_number'] = $staff_phone;
                $temp['onboarded'] = true;
                $temp['message'] = 'Successfully onboarded staff.';

                array_push($response, $temp);
            }

            return response()->json(['staff'=>$response]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Onboard staff method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function RequestPhysicalCard(Request $request)
    {
        try{
            $request->validate([
                'business_id'=>'required|uuid',
                'name' => 'required',
                'address' =>  'nullable|string',
                'phone_number' =>  'nullable|string|max:14',
                'card_type'=>'required|string',
                'state'=>'string',
                'lga'=>'string',
            ]);


            $response = BusinessCardRequest::on('mysql::write')->create([

                'business_id' => $request->business_id,
                'name' => $request->name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'state' => $request->state,
                'lga' => $request->lga,
                'physical_card'=>true,
                'virtual_card'=>false,
                'card_type'=>$request->card_type,

            ]);

            return response()->json([
                "message" => "Request sent successfully",
                'response' => $response,
                'status' => 'success',
            ], 201);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Request Physical card method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function RequestVirtualCard(Request $request)
    {
        try{
            $this->validate($request, [
                'business_id'=>'required',
                'name' => 'required',
                'address' =>  'nullable|string',
                'currency' =>  'nullable|string',
                'card_type' =>  'nullable|string',
                'amount' =>  'nullable|string'
            ]);


            $response = BusinessCardRequest::on('mysql::write')->create([
                'business_id' => $request->business_id,
                'name' => $request->name,
                'address' => $request->address,
                'currency' => $request->currency,
                'card_type' => $request->card_type,
                'amount_to_fund' => $request->amount,
                'physical_card'=>false,
                'virtual_card'=>true,
            ]);

            return response()->json([
                "message" => "Request sent successfully",
                'response' => $response,
                'status' => 'success',
            ], 201);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Request Virtual card method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);

            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function kycUpdateOne(Request $request){

        try{
            $request->validate([
                'business_id'=>'required|uuid',
                'business_name'=>'string',
                'first_name'=>'string',
                'last_name'=>'string',
                'kin_name'=>'string',
                'kin_phone'=>'string',
                'country'=>'string',
                'state'=>'string',
                'city'=>'string',
                'lga'=>'string',
                'address'=>'string',
                'gender'=>'string'
            ]);

            //return $request;

            $kyc = BusinessKyc::on('mysql::write')->updateOrCreate(['business_id'=>$request->business_id],[
                'business_id'=>$request->business_id,
                'owner_first_name'=>$request->first_name,
                'owner_last_name'=>$request->last_name,
                'business_name'=>$request->business_name,
                'kin_name'=>$request->kin_name,
                'kin_phone'=>$request->kin_phone,
                'country'=>$request->country,
                'state'=>$request->state,
                'city'=>$request->city,
                'lga'=>$request->lga,
                'address'=>$request->address,
                'gender'=>$request->gender,
            ]);

            return response()->json(['kyc'=>$kyc, 'message'=>'Success']);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - kycUpdateOne method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function kycUpdateTwo(Request $request){

        try{
            $request->validate([
                'business_id'=>'required|uuid',
                'cac_photo'=>'file',
                'memorandum_photo'=>'file',
                'id_photo'=>'file',
                'bvn'=>'string',
                'address_photo'=>'file',
            ]);

            $cacPhoto = '';
            $idPhoto = '';
            $addressPhoto = '';
            $memoPhoto = '';

            if($request->hasFile('cac_photo')){
                $cacPhoto = $this->utility->saveFile($request->file('cac_photo'), 'kyc/business', 'cac-photo');
            }

            if($request->hasFile('id_photo')){
                $idPhoto = $this->utility->saveFile($request->file('id_photo'), 'kyc/business', 'id-photo');
            }

            if($request->hasFile('address_photo')){
                $addressPhoto = $this->utility->saveFile($request->file('address_photo'), 'kyc/business', 'proof-of-address');
            }

            if($request->hasFile('memorandum_photo')){
                $memoPhoto = $this->utility->saveFile($request->file('memorandum_photo'), 'kyc/business', 'memorandum-photo');
            }

            $kyc = BusinessKyc::on('mysql::write')->updateOrCreate(['business_id'=>$request->business_id],[
                'business_id'=>$request->business_id,
                'cac_url'=>$cacPhoto,
                'id_url'=>$idPhoto,
                'address_url'=>$addressPhoto,
                'bvn'=>$request->bvn,
                'memorandum_url'=>$memoPhoto,
            ]);

            return response()->json(['kyc'=>$kyc, 'message'=>'Success']);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - kycUpdateTwo method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function saveBeneficiary(Request $request){
        try{
            $request->validate([
                'business_id'=>'required|uuid',
                'account_number'=>'required|numeric',
                'account_type'=>'required|string',
            ]);

            $exists = Beneficiaries::on('mysql::read')->where([['business_id', $request->user_id], ['beneficiary_account_number', $request->account_number], ['account_type',$request->account_type]])->first();

            if($exists){
                return response()->json(['message'=>'Beneficiary Already Saved.']);
            }


            $ben = Beneficiaries::on('mysql::write')->create([
                'business_id'=>$request->business_id,
                'beneficiary_account_number'=>$request->account_number,
                'account_type'=>$request->account_type,
            ]);

            return response()->json(['message'=>'Beneficairy Saved Successfully', 'beneficiary'=>$ben], 200);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Save beneficiary method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 404);
        }

    }

    public function getBeneficiaries($business_id){
        try{
            $user = Business::on('mysql::read')->findOrFail($business_id);
            $accounts = array();

            foreach($user->beneficiaries as $ben){

                $acc = AccountNumber::on('mysql::read')->where('account_number', $ben['beneficiary_account_number'])->first();
                $ben['account_details'] = $acc;

                $accounts[] = $ben;
            }

            return response()->json(['message'=>'Beneficairies Retrieved Successfully', 'beneficiaries'=>$accounts], 200);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BusinessController - Get beneficiaries method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 404);
        }

    }

    public function removeBeneficiary(Request $request){
        try{
            $request->validate([
                'business_id'=>'required|uuid',
                'account_number'=>'required|numeric',
            ]);

            $exists = Beneficiaries::on('mysql::read')->where([['business_id', $request->business_id], ['beneficiary_account_number', $request->account_number]])->first();

            if(!$exists){
                return response()->json(['message'=>'Beneficiary Not Found.'], 404);
            }

            $check = $exists->delete();

            return response()->json(['message'=>'Beneficairy Removed Successfully', 'removed'=>$check], 200);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>''], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 404);
        }

    }

}
