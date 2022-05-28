<?php

namespace App\Http\Controllers\Savings;

use App\Models\BreakSaving;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Mail\CreditEmail;
use App\Mail\DebitEmail;
use App\Mail\SavingsCreditMail;
use App\Mail\SavingsDebitMail;
use App\Mail\TransactionMail;
use App\Models\AccountNumber;
use App\Models\PaystackRefRecord;
use App\Models\SavedCard;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use App\Models\WalletTransaction;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SavingController extends Controller
{
    protected $utility;
    protected $jwt;

    function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->utility = new Functions();
    }

    public function initSave(Request $request){
        try{
            $request->validate([
                'userId'=>'required',
                'name'=>'required',
                'amount'=>'required|integer|numeric|gt:0',
                'cycle'=>'required|string',
                'duration'=>'required|integer',
                'description'=>'nullable',
                'start_date'=>'required',
                'end_date'=>'required',
                'overall_amount'=>'required|numeric|gt:0',
                'type'=>'required',
            ]);

            //Get user and check if User exists
            $user = User::on('mysql::read')->findOrFail(Auth::id());

            if(!$user){
                return response()->json(['message'=>'Unable to find User'], 404);
            }

            $pSaving = intval($request->overall_amount);

            //$date=date_create();
            //$date = date_add($date,date_interval_create_from_date_string($request->duration." months"));
            //echo date_format($date,"Y-m-d");

            $startDate = date('Y-m-d', strtotime($request->start_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));
            //$startDate = date_create($request->start_date);
            //$endDate = date_create($request->end_date);
            //$ymd = date_create_from_format('d-m-Y', $request->start_date)->format('Y-m-d');

            //return $startDate. ' '. $endDate.' ';//. $ymd;
            $nextSave = '';
            $type = $request->type;
            if($type == 'Recurrent' || $type == 'Target'){
                $type = 'Personal';
            }
            
            if(strtoupper($request->cycle) == 'DAILY'){
                //Calculate projected savings using 30 days a month estimate
                //$pSaving = intval($request->amount) * intval($request->duration) * 30;
                $nextSave = '1 day';
            }elseif (strtoupper($request->cycle) == 'WEEKLY'){
                //Calculate projected savings using a 4 weeks a month estimate
                //$pSaving = intval($request->amount) * intval($request->duration) * 4;
                $nextSave = '1 week';
            }elseif (strtoupper($request->cycle) == 'MONTHLY'){
                //Calculate projected savings monthly
                //$pSaving = intval($request->amount) * intval($request->duration);
                $nextSave = '1 month';
            }
            //return var_dump($startDate);
            $sd = new DateTime($startDate);
            //return $sd;
            //$nextDate = date_add($sd, date_interval_create_from_date_string($nextSave));
            $nextDate = date('Y-m-d', strtotime($startDate.$nextSave));

            //return var_dump($nextDate);

            $data = array(
                'userId' => $user->id,
                'amount'=>$request->amount,
                'name'=>$request->name,
                'description'=>$request->description,
                'cycle'=>$request->cycle,
                'balance'=>0,
                'status'=>'active',
                'start_save'=>$startDate,
                'next_save'=>$nextDate,
                'end_date'=>$endDate,
                'projected_saving'=>$pSaving,
                'type'=>$type,
                'card_signature'=>'',
                'duration'=>$request->duration
            );

            $savings = Saving::on('mysql::write')->create($data);
            $walletFunded = $this->walletFunding($savings);
            //'account'=>$savings,
            return response()->json(['account'=>$savings,'message'=>$walletFunded], 200);

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'User not found.'], 404);
        }
        catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'SavingController - Create Personal Savings Account method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 404);
        }
    }

    public function walletFunding($savings){
        $msg = '';
        $user = User::on('mysql::write')->find($savings->userId);

        $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
        $savinWt = WalletTransaction::on('mysql::write')->create([
            'wallet_id'=> $user->wallet->id,
            'type'=>'Debit',
            'amount'=>$savings->amount,
            'description'=>'Savings Account Deposit',
            'receiver_account_number'=>$acc->account_number,
            'receiver_name'=>$user->name,
            'transfer'=>false,
        ]);

        //If wallet has insufficient funds
        if($user->wallet->balance < $savings->amount || $savings->amount <= 0){
            $savinWt->update(['status'=>'failed',]);
            $msg = 'Insufficient funds. Please fund your wallet or use a debit/credit card to deposit into your Savings Account.';
        }
        //Users wallet balance is sufficient
        elseif ($user->wallet->balance >= $savings->amount){
            //Debit wallet
            $currentBalance = floatval($user->wallet->balance);
            $amount = floatval($savings->amount);
            $depositAmount = floatval($savings->balance) + $amount;
            $newBalance = $currentBalance - $amount;
            $currentNextSave = $savings->next_save;
            $nextSave = '';
            if(strtoupper($savings->cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($savings->cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($savings->cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }
            
            //return $savings->next_save;
            if($savings->next_save == null) {
                //return 'next save is null';
                //Update savings account balance
                Saving::on('mysql::write')->where('id', $savings->id)->update([
                    'balance' => $depositAmount,
                    'start_save' => date('Y-m-d H:i:s'),
                    'next_save' => date('Y-m-d',strtotime(date('Y-m-d').$nextSave)),
                ]);
            }else{
                
                $today = date('Y-m-d');
                $nextDate = $savings->next_save;

                $todaysDate = strtotime($today);
                $nextSaveDate = strtotime($nextDate);

                if($nextSaveDate > $todaysDate){
                    
                    $midAmount = intval($savings->mid_payment_amount) + $amount;

                    Saving::on('mysql::write')->where('id', $savings->id)->update([
                        'balance' => $depositAmount,
                        'mid_payment_amount' => $midAmount,
                        'mid_payment' => 1
                    ]);
                }elseif ($nextSaveDate == $todaysDate || $nextSaveDate < $todaysDate){
                    Saving::on('mysql::write')->where('id', $savings->id)->update([
                        'balance' => $depositAmount,
                        'next_save' => date('Y-m-d',strtotime($currentNextSave.$nextSave)),
                        //date_add($currentNextSave, date_interval_create_from_date_string($nextSave))
                    ]);
                }
            }
            //Update wallet balance
            $user->wallet()->update(['balance'=>$newBalance]);
            
            /*$user->wallet->transactions()->create([
                'transaction_amount'        => $amount,
                'current_balance'           => $currentBalance,
                'new_balance'               => $newBalance,
                'transaction_type'          => 'Debit',
                'transaction_description'   => 'Funded Savings Account',
                'status'                    => 'completed',
                'transaction_reference'     => $savings->id
            ]);*/
            //Add to wallet transaction table
            $savinWt->update(['status'=>'success']);
            //Add to savings transaction table
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$savings->userId,
                'amount'=>$savings->amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Deposit'
            );
            
            //$this->savingsTransaction->save($sTransactionData);
            SavingTransaction::on('mysql::write')->create($sTransactionData);
            
            Mail::to($user->email)->send(new DebitEmail($user->name,$amount, $savinWt, $user->wallet, $acc));   
            Mail::to($user->email)->send(new SavingsCreditMail($user->name,$amount));
            
            $msg = 'Successfully deposited funds into your Savings Account.';
        }

        return $msg;
    }

    public function listAccounts($userId){
        $accs = Saving::on('mysql::read')->where('userId', $userId)->where(function ($query){
            $query->where('type', 'Personal')->orWhere('type', 'Flex')->orWhere('type', 'Fixed');
        })->orderBy('created_at', 'DESC')->get();
        if(!$accs){
            return response()->json(['message'=>'No Account found.'], 404);
        }
        return response()->json(['accounts'=>$accs], 200);
    }

    public function getAccount($id){
        $accs = Saving::on('mysql::read')->where('id', $id)->where(function ($query){
            $query->where('type', 'Personal')->orWhere('type', 'Flex')->orWhere('type', 'Fixed');
        })->first();
        if(!$accs){
            return response()->json(['message'=>'No Account found.'], 404);
        }
        return response()->json(['account'=>$accs], 200);
    }

    public function getAccountHistory($account_id){
        try{
            $userId =Auth::id();
            $id = $account_id;

            $accs = SavingTransaction::on('mysql::read')->where([['savingsId', $id], ['userId', $userId]])->get();

            if(!$accs){
                return response()->json(['message'=>'Unable to retrieve Users account history.'], 404);
            }
            return response()->json(['history'=>$accs], 200);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function fundSavingsAccountFromCard(Request $request){
        try{
            $request->validate([
                'payment_ref'=>'required',
                'account_id'=>'required',
                'user_id'=>'required',
                'save_card'=>'required'
            ]);

            $savingsId = $request->account_id;
            $userId = $request->user_id;
            $ref = $request->payment_ref;

            $checkRef = PaystackRefRecord::where('ref', $ref)->first();

            if($checkRef && $checkRef->status == 'success'){
                return response()->json(['message'=>'Already processed this transaction.']);
            }elseif (!$checkRef){
                $checkRef = PaystackRefRecord::create([
                    'ref'=>$ref,
                    'status'=>'pending',
                ]);
            }

            $savingsAcc =  Saving::on('mysql::write')->find($savingsId);

            if(!$savingsAcc){
                return response()->json(['message'=>'Unable to retrieve account.'], 404);
            }

            $user = User::on('mysql::read')->find($savingsAcc->userId);
            if(!$user){
                return response()->json(['message'=>'Unable to retrieve User.'], 404);
            }

            $res = $this->utility->verifyPaystackPayment($ref);

            $verified = $res['status'];
            $amount = intval($res['amount'])/100;
            $card = $res['card'];
            $msg = '';
            $cardMsg = false;

            if($verified == -1){
                $msg = 'We were unable to initiate the process of verifying your payment status. Please contact our customer support lines with your transaction reference for help.';
                $checkRef->update(['status'=>'failed']);
            }elseif ($verified == 404){
                $msg = 'We could not find your payment transaction reference.';
                $checkRef->update(['status'=>'failed']);
            }elseif ($verified == 400){
                $msg = 'Unfortunately, Transaction failed.';
                $checkRef->update(['status'=>'failed']);
            }elseif ($verified == 503){
                $msg = 'Unable to verify transaction. Please contact our customer support lines with your transaction reference for help.';
                $checkRef->update(['status'=>'failed']);
            }elseif ($verified == 100){

                $updateArray = array();
                $msg = 'Successful payment';
                $midPay = 0;
                $midPayAmount = 0;
                $currNextSave = $savingsAcc->next_save;
                $newBalance = intval($savingsAcc->balance) + intval($amount);
                $today = date('Y-m-d');

                $todaysDate = strtotime($today);
                $nextDate = strtotime($currNextSave);
                $nextSave = '';
                if(strtoupper($savingsAcc->cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($savingsAcc->cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($savingsAcc->cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }

                if($savingsAcc->next_save == null){
                    //Update savings account balance
                    $savingsAcc->update([
                        'balance' => $newBalance,
                        'next_save' => date('Y-m-d',strtotime(date('Y-m-d').$nextSave))
                    ]);
                }else {
                    //Check if it is a payment before the next due date
                    if ($nextDate > $todaysDate) {
                        $midAmount = intval($savingsAcc->mid_payment_amount) + $amount;
                        $updateArray['balance'] = $newBalance;
                        $updateArray['mid_payment'] = 1;
                        $updateArray['mid_payment_amount'] = $midAmount;
                    } elseif ($nextDate == $todaysDate) {
                        $updateArray['balance'] = $newBalance;
                        $updateArray['next_save'] = date('Y-m-d',strtotime($currNextSave.$nextSave));
                    }

                    //return $updateArray;

                    $updateArray['card_added'] = $request->save_card;
                    //Update Savings account balance
                    Saving::on('mysql::write')->where('id', $savingsAcc->id)->update($updateArray);
                }
                //Record transaction in Savings Transaction
                $sTransactionData = array(
                    'savingsId'=>$savingsAcc->id,
                    'userId'=>$savingsAcc->userId,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Deposit'
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);
                $checkRef->update(['status'=>'success']);
                Mail::to($user->email)->send(new SavingsCreditMail($user->name,$amount));
                //If User wants to save card for auto payment, Store card details
                if($request->save_card == 1){
                    $cardExists = SavedCard::where('signature', $card['signature'])->exists();

                    if($cardExists){
                        $cardMsg = false;
                        $cardInfo = 'Card already saved';
                    }else{
                        $cardDets = array(
                            'auth_code'=>$card['authorization_code'],
                            'card_type'=>$card['card_type'],
                            'last4'=>$card['last4'],
                            'exp_month'=>$card['exp_month'],
                            'exp_year'=>$card['exp_year'],
                            'bin'=>$card['bin'],
                            'bank'=>$card['bank'],
                            'channel'=>$card['channel'],
                            'signature'=>$card['signature'],
                            'reuseable'=>$card['reusable'],
                            'country_code'=>$card['country_code'],
                            'account_name'=>$card['account_name'],
                            'user_id'=>$savingsAcc->userId,
                            'user_email'=>$user->email
                        );

                        $cardSignature = $card['signature'];

                        $isSaved = SavedCard::on('mysql::write')->create($cardDets);
                        Saving::on('mysql::write')->where('id', $savingsAcc->id)->update(['card_signature'=>$cardSignature]);

                        if($isSaved){
                            $cardMsg = true;
                        }
                    }
                }
            }

            return response()->json(['card_saved'=>$cardMsg, 'message'=>$msg], 200);

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function fundSavingsAccountFromWallet(Request $request){
        try{
            $request->validate([
                'account_id'=>'required',
                'user_id'=>'required'
            ]);
            $user_id = Auth::id();
            $savings = Saving::on('mysql::write')->find($request->account_id);

            if(!$savings){
                return response()->json(['message'=>'Unable to retrieve account.'], 404);
            }

            if($savings->userId != $user_id){
                return response()->json(['message'=>'You\'re trying to deposit in another users Savings Account.'], 200);
            }
            
            $walletFunded = $this->walletFunding($savings);

            $updated = Saving::on('mysql::read')->find($savings->id);
            //'account'=>$savings,
            return response()->json(['account'=>$updated,'message'=>$walletFunded], 200);

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function fund_user_wallet_transfer(Request $request)
    {
        try{
            $request->validate([
                'reference'=>'required',
                'user_id'=>'required',
                'account_id'=>'required',
            ]);
        }catch(ValidationException $exception){
            return response()->json(['errors'=>$exception->errors(), 'message'=>$exception->getMessage()]);
        }
        $paystack_payment_reference = $request->reference;
        //$amount = $request->amount;
        $userId = $request->user_id;
        //$key = config('app.paystack');
        $msg = '';

        // verify the payment
        try{
            $user = User::on('mysql::read')->findOrFail($userId);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Could not find User.'], 404);
        }
        if(!$user){
            return response()->json(['message'=>'Could not find User.'], 404);
        }

        try{
            $savings = Saving::on('mysql::write')->findOrFail($request->account_id);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Savings Account not found.'], 404);
        }

         //$reference ='WALLET-'. $this->user->generate_transaction_reference();

         $verification_status = $this->utility->verifyMonifyPayment($paystack_payment_reference);

         //return $verification_status;

         $amount = intval($verification_status['amount']);
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
            $updateArray = array();
            $midPay = 0;
            $midPayAmount = 0;
            $currNextSave = $savings->next_save;
            $newBalance = intval($savings->balance) + intval($amount);
            $today = date('Y-m-d');

            $todaysDate = strtotime($today);
            $nextDate = strtotime($currNextSave);
            $nextSave = '';
            if(strtoupper($savings->cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($savings->cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($savings->cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }

            //Check if it is a payment before the next due date
            if ($nextDate > $todaysDate) {
                $midAmount = intval($savings->mid_payment_amount) + $amount;
                $updateArray['balance'] = $newBalance;
                $updateArray['mid_payment'] = 1;
                $updateArray['mid_payment_amount'] = $midAmount;
            } elseif ($nextDate == $todaysDate) {
                $updateArray['balance'] = $newBalance;
                $updateArray['next_save'] = date('Y-m-d',strtotime($currNextSave.$nextSave));
            }
            
            //Update Savings account balance
            $savings->update($updateArray);
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$userId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d'),
                'type'=>'Deposit'
            );
            $walTransaction = SavingTransaction::on('mysql::write')->create($sTransactionData);
            Mail::to($user->email)->send(new SavingsCreditMail($user->name,$amount));
        }

        return response()->json(['message'=>$msg]);
    }

    public function closeAccount(Request $request){
        try{

            $request->validate([
                'user_id'=>'required',
                'account_id'=>'required',
                'reason'=>'nullable',
                'explanation'=>'nullable',
                'transaction_pin' => 'required|numeric'
            ]);

            $accId= $request->account_id;
            //$userId = $request->user_id;
            $userId = Auth::id();
            $reason = $request->reason;
            $exp = $request->explanation;
            $pin = $request->transaction_pin;

            $acc = Saving::on('mysql::write')->find($accId);
            if(!$acc){
                return response()->json(['message'=>'Unable to retrieve Account.'], 404);
            }
            $user = User::on('mysql::write')->find($acc->userId);
            if(!$user){
                return response()->json(['message'=>'Unable to retrieve User.'], 404);
            }

            $useracc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();

            $savinWt = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=> $user->wallet->id,
                'type'=>'Credit',
                'amount'=>$acc->balance,
                'description'=>'Savings Account Withdrawal',
                'receiver_account_number'=>$useracc->account_number,
                'receiver_name'=>$user->name,
                'transfer'=>false,
            ]);

            if(empty($user->transaction_pin)){
                $savinWt->update(['status'=>'failed']);
                return response()->json(['message'=>'Transaction Pin not set.', 'status'=> 422], 422);
            }

            if(!Hash::check($pin, $user->transaction_pin))
            {
                $savinWt->update(['status'=>'failed']);
                return response()->json(['message'=>'Incorrect Pin!','status'=> 420], 420);
            }

            $savingsBalance = intval($acc->balance);
            $currWalletBalance = intval($user->wallet->balance);
            $newWalletBalance = $savingsBalance + $currWalletBalance;
            $today = date('Y-m-d');
            $endDate = $acc->end_date;

            $todayTime = strtotime($today);
            $endtime = strtotime($endDate);
            if($endtime > $todayTime){
                $newWalletBalance = $newWalletBalance - ($savingsBalance * $acc->penalty);
            }
            //Update wallet balance
            $user->wallet()->update(['balance'=>$newWalletBalance]);
            
            //$deleteSavingsTransaction = SavingTransaction::where([['savingsId', $acc->id], ['userId', $acc->userId]])->delete();

            $acc->delete();
            $break = BreakSaving::on('mysql::write')->create([
                'user_id'=>$userId,
                'account_id'=>$accId,
                'reason'=>$reason,
                'explanation'=>$exp
            ]);

            //Add to wallet transaction table
            $savinWt->update([
                'status'=>'success',
            ]);
            Mail::to($user->email)->send(new SavingsDebitMail($user->name,$savingsBalance));
            Mail::to($user->email)->send(new CreditEmail($user->name,$savingsBalance, $savinWt, $user->wallet));
            return response()->json(['message'=>'Savings Account successfully closed.'], 200);
        }catch(ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'SavingController - (close account)',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function withdrawAccount(Request $request){
        try{

            $request->validate([
                'user_id'=>'required',
                'account_id'=>'required',
                'transaction_pin' => 'required|numeric',
            ]);

            $accId= $request->account_id;
            $userId = $request->user_id;
            $pin = $request->transaction_pin;

            $acc = Saving::on('mysql::write')->find($accId);
            if(!$acc){
                return response()->json(['message'=>'Unable to retrieve Account.'], 404);
            }
            $user = User::on('mysql::write')->find($acc->userId);
            if(!$user){
                return response()->json(['message'=>'Unable to retrieve User.'], 404);
            }

            $useracc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
            //$deleteSavingsTransaction = SavingTransaction::where([['savingsId', $acc->id], ['userId', $acc->userId]])->delete();
            
            //Add to wallet transaction table
            $savinWt = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=> $user->wallet->id,
                'type'=>'Credit',
                'amount'=>$acc->balance,
                'description'=>'Savings Account Withdrawal',
                'receiver_account_number'=>$useracc->account_number,
                'receiver_name'=>$user->name,
                'transfer'=>false,
                'status'=>'success',
            ]);

            if(empty($user->transaction_pin)){
                $savinWt->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Transaction Pin not set.', 'status'=> 422], 422);
            }

            if(!Hash::check($pin, $user->transaction_pin))
            {
                $savinWt->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Incorrect Pin!','status'=> 420], 420);
            }

            $savingsBalance = intval($acc->balance);
            $currWalletBalance = intval($user->wallet->balance);
            $today = date('Y-m-d');
            $endDate = $acc->end_date;
            $newWalletBalance = 0;
            
            $todayTime = strtotime($today);
            $endtime = strtotime($endDate);
            if($endtime > $todayTime){
                $newWalletBalance = ($savingsBalance - ($savingsBalance * $acc->penalty)) + $currWalletBalance;
            }else{
                $newWalletBalance = $savingsBalance + $currWalletBalance + ($savingsBalance * $acc->interest_rate);
            }
            //Update wallet balance
            $user->wallet()->update(['balance'=>$newWalletBalance]);
            $acc->update(['balance'=>0]);
            $savinWt->update([
                'status'=>'success',
            ]);

            Mail::to($user->email)->send(new SavingsDebitMail($user->name,$savingsBalance));
            Mail::to($user->email)->send(new CreditEmail($user->name,$savingsBalance, $savinWt, $user->wallet));
            return response()->json(['message'=>'Savings Account Withdrawal successfull .'], 200);
        }catch(ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'SavingController - (close account)',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function deleteCard(Request $request){
        try{
            $request->validate([
                'user_id'=>'required',
                'card_signature'=>'required|string',
                'card_id'=>'required'
            ]);

            $cardId = $request->card_id;
            $signature = $request->card_signature;
            $userId = $request->user_id;

            $card = SavedCard::on('mysql::write')->where([['id', $cardId], ['signature', $signature], ['user_id', $userId]])->get()->first();

            //return $card;

            if($card){
                $card->delete();
                return response()->json(['message'=>'Card successfully deleted.'], 200);
            }else{
                return response()->json(['message'=>'Unable to retrieve Card.'], 404);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function getCards($id){

        $cards = SavedCard::on('mysql::read')->where('user_id', $id)->get();

        if($cards){
            //$card->delete();
            return response()->json(['message'=>'Cards retrieved successfully.', 'cards'=>$cards], 200);
        }else{
            return response()->json(['message'=>'Unable to retrieve Cards.'], 404);
        }

    }

    public function getSavingAccount($id){
        try{
            $cards = Saving::on('mysql::read')->find($id);

            if($cards){
                //$card->delete();
                return response()->json(['message'=>'Account retrieved successfully.', 'account'=>$cards], 200);
            }else{
                return response()->json(['message'=>'Unable to retrieve Account.'], 404);
            }
        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'SavingController - Get savings account.',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 422);
        }

    }

    public function extendSavings(Request $request)
    {
        try
        {
            $request->validate([
                'amount'=>'required',
                'cycle'=>'required',
                'duration'=>'required',
                'overall_amount'=>'required',
                'user_id'=>'required',
                'account_id'=>'required',
                'pin'=>'required',             
            ]);
            //'password'=>'required'

            $userId = $request->user_id;
            $amount = $request->amount;
            $cycle = $request->cycle;
            $duration = $request->duration;
            $overallAmount = $request->overall_amount;
            $accountId = $request->account_id;
            $pin = $request->pin;
            
            //Get user and check if User exists
            $user = User::on('mysql::read')->find($request->user_id);

            if(!$user){
                return response()->json(['message'=>'Unable to find User'], 404);
            }

            $account = Saving::on('mysql::write')->where([['userId', $userId], ['type', 'Personal'], ['id' ,$accountId]])->get()->first();

            if(!$account)
            {
                return response()->json(['message'=>'Unable to find Users account'], 404);
            }

            if(empty($user->transaction_pin)){
                return response()->json(['message'=>'Transaction Pin not set.'], 422);
            }
    
            if(!Hash::check($pin, $user->transaction_pin))
            {
                return response()->json(['message'=>'Incorrect Pin!'], 404);
            }

            $ext = $duration.' months';

            $endDate = date('Y-m-d', strtotime($account->end_date.$ext));

            $account->update([
                'amount'=>$amount,
                'cycle'=>$cycle,
                'duration'=>$duration,
                'projected_saving'=>$overallAmount,
                'end_date'=>$endDate,
            ]);
            
            if($account)
            {
                return response()->json(['message'=>'Successfully extended savings.'], 200);
            }
        }
        catch(ValidationException $exception)
        {
            return response()->json(['message', $exception->getMessage(), 'errors', $exception->errors()], 422);
        }
    }
}
