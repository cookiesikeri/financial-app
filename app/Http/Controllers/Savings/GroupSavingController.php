<?php

namespace App\Http\Controllers\Savings;

use App\Models\GroupSaving;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\AccountNumber;
use App\Models\JoinRequest;
use App\Models\PaystackRefRecord;
use App\Models\SavedCard;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class GroupSavingController extends Controller
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
                'name'=>'required|string',
                'amount'=>'required|integer|numeric|gt:0',
                'cycle'=>'required|string',
                'num_of_members'=>'required|integer|numeric',
                'start_date'=>'required',
                'end_date'=>'required',
                'description'=>'nullable',
                'overall_amount'=>'required|numeric|gt:0',
                'access'=>'required'
            ]);
            try{
                //Get user and check if User exists
                $user = User::on('mysql::read')->findOrFail($request->userId);
            }catch(ModelNotFoundException $e){
                return response()->json(['message'=>'User not found.'], 404);
            }
            if(!$user){
                return response()->json(['error'=>'Unable to find User'], 404);
            }

            $pSaving = $request->overall_amount;
            $startDate = date('Y-m-d', strtotime($request->start_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));

            //$date=date_create();
            //$date = date_add($date,date_interval_create_from_date_string($request->duration." months"));
            //echo date_format($date,"Y-m-d");
            /* $today = date('d');
            $reqDay = intval($request->day_of_month);

            if($today < $reqDay)            {
                $nextSave = date('Y-m-').$request->day_of_month;
            }elseif($today > $reqDay)
            {
                $mnt = date('m')+1;
            } */
            $nextSave = '';
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
            //$sd = date_create($startDate);
            //return $pSaving;
            //$nextDate = date_add($sd,date_interval_create_from_date_string($nextSave));
            $nextDate = date('Y-m-d', strtotime($startDate.$nextSave));

            $data = array(
                'userId' => $request->userId,
                'amount'=>$request->amount,
                'name'=>$request->name,
                'description'=>$request->description,
                'cycle'=>$request->cycle,
                'access'=>$request->access,
                'balance'=>0,
                'status'=>'active',
                'end_date'=>$endDate,
                'start_save'=>$startDate,
                'next_save'=>$nextDate,
                'projected_saving'=>$pSaving,
                'type'=>'Group',
                'card_signature'=>'',
                'num_of_members'=>$request->num_of_members
            );

            //return $data;

            $savings = Saving::on('mysql::write')->create($data);

            //Create A Group savings member
            $member = array(
                'account_id'=>$savings->id,
                'member_id'=>$savings->userId,
                'admin'=>1,
                'disburse'=>0,
                'mid_payment'=>0,
                'card_signature'=>'',
                'disburse_to'=>'',
            );

            $groupMember = GroupSaving::on('mysql::write')->create($member);

            if(!$groupMember){
                return response()->json(['message'=>'Unable to create group member'], 422);
            }

            return response()->json(['account'=>$savings,'message'=>'Group Savings account created.'], 200);

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
            $cardInfo='';

            $checkRef = PaystackRefRecord::where('ref', $ref)->first();

            if($checkRef && $checkRef->status == 'success'){
                return response()->json(['message'=>'Already processed this transaction.']);
            }elseif (!$checkRef){
                $checkRef = PaystackRefRecord::create([
                    'ref'=>$ref,
                    'status'=>'pending',
                ]);
            }

            $savingsAcc =  Saving::on('mysql::write')->findOrFail($savingsId);

            if(!$savingsAcc){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            $member = GroupSaving::on('mysql::write')->where([['account_id', $savingsId], ['member_id', $userId]])->get()->first();

            //return $member;

            if(!$member){
                return response()->json(['error'=>'User is not a member of this Group Savings.'], 404);
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
                $msg = 'We could not find your payment transaction reference. Your payment might have been declined. Please contact our customer support lines with your transaction reference for help.';
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
                $memBal = intval($member->balance) + intval($amount);
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

                    $member->update(['balance'=>$memBal]);
                }else {
                    //Check if it is a payment before the next due date
                    if ($nextDate > $todaysDate) {
                        $member->update(['mid_payment'=>1, 'balance'=>$memBal]);
                    } elseif ($nextDate == $todaysDate) {
                        $updateArray['balance'] = $newBalance;
                        $updateArray['next_save'] = date('Y-m-d',strtotime($currNextSave.$nextSave));
                    }

                    //return $updateArray;

                    $updateArray['card_added'] = $request->save_card;
                    //Update Savings account balance
                    $savingsAcc->update(['balance'=>$newBalance]);
                }
                //Record transaction in Savings Transaction
                $sTransactionData = array(
                    'savingsId'=>$savingsAcc->id,
                    'userId'=>$userId,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Deposit'
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);
                $checkRef->update(['status'=>'success']);
                //If User wants to save card for auto payment, Store card details

                if($request->save_card == 1){
                    $cardExists = SavedCard::where('signature', $card['signature'])->exists();

                    if($cardExists){
                        //$cardMsg = true;
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
                            'user_email'=>''
                        );

                        $isSaved = SavedCard::on('mysql::write')->create($cardDets);

                        //GroupSaving::where([['member_id', $userId], ['account_id', $savingsId]])->update(['card_signature'=>$card['signature']]);

                        if($isSaved){
                            $cardMsg = true;
                            $cardInfo = 'Card saved';
                        }
                    }
                }else{
                    $cardInfo = 'Card not saved';
                }
            }

            return response()->json(['card_saved'=>$cardMsg, 'message'=>$msg, 'card_info'=>$cardInfo], 200);

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()],422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 420);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
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

        $member = GroupSaving::on('mysql::write')->where([['account_id', $request->account_id], ['member_id', $userId]])->get()->first();

        //return $member;

        if(!$member){
            return response()->json(['error'=>'User is not a member of this Group Savings.'], 404);
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
            $memBal = intval($member->balance) + intval($amount);
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
                $member->update(['mid_payment'=>1, 'balance'=>$memBal]);
            } elseif ($nextDate == $todaysDate) {
                $updateArray['balance'] = $newBalance;
                $updateArray['next_save'] = date('Y-m-d',strtotime($currNextSave.$nextSave));
            }

            $savings->update(['balance' => $newBalance]);
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$userId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d'),
                'type'=>'Deposit'
            );
            $walTransaction = SavingTransaction::on('mysql::write')->create($sTransactionData);
        }

        return response()->json(['message'=>$msg]);
    }

    public function fundSavingsAccountFromWallet(Request $request){
        try{
            $request->validate([
                'account_id'=>'required',
                'user_id'=>'required'
            ]);
            //$userId = $request->user_id;
            $userId = Auth::id();
            $savings = Saving::on('mysql::write')->find($request->account_id);

            if(!$savings){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            /*if($savings->userId != $request->user_id){
                return response()->json(['error'=>'You\'re trying to deposit in another users Savings Account.']);
            }*/

            //$member = GroupSaving::where('member_id', $userId)->get();

            $walletFunded = $this->walletFunding($savings, $userId);
            //'account'=>$savings,
            $savings = Saving::on('mysql::write')->find($request->account_id);
            return response()->json(['account'=>$savings,'message'=>$walletFunded], 200);

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function walletFunding($savings, $userId){
        $msg = '';
        $user = User::on('mysql::write')->find($userId);
        $member = GroupSaving::on('mysql::write')->where([['member_id', $userId], ['account_id', $savings->id]])->get()->first();

        if(!$user){
            return 'Unable to retrieve User.';
        }

        if(!$member){
            return 'User is not a member of this Group Savings.';
        }

        $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
        $walTr = WalletTransaction::on('mysql::write')->create([
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
            $walTr->update(['status'=>'failed',]);
            $msg = 'Insufficient funds. Please fund your wallet or use a debit/credit card to deposit into your Savings Account.';
        }
        //Users wallet balance is sufficient
        elseif ($user->wallet->balance >= $savings->amount){
            //Debit wallet
            $currentBalance = intval($user->wallet->balance);
            $amount = intval($savings->amount);
            $depositAmount = intval($savings->balance) + $amount;
            $memBal = intval($member->balance) + $amount;
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
            //return $newBalance;
            if($savings->next_save == null) {
                //Update savings account balance
                
                $savings->update([
                    'balance' => $depositAmount,
                    'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                ]);

                $member->update(['balance'=>$memBal]);
            }else{
                
                $today = date('Y-m-d');
                $nextDate = $savings->next_save;

                $todaysDate = strtotime($today);
                $nextSaveDate = strtotime($nextDate);

                if($nextSaveDate > $todaysDate){
                    $member->update(['mid_payment'=>1, 'balance'=>$memBal]);
                }/*elseif ($nextSaveDate == $todaysDate){

                }*/

                Saving::on('mysql::write')->where('id', $savings->id)->update([
                    'balance' => $depositAmount
                ]);
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
           $walTr->update(['status'=>'success',]);
            //Add to savings transaction table
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$userId,
                'amount'=>$savings->amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Deposit'
            );
            //$this->savingsTransaction->save($sTransactionData);
            SavingTransaction::on('mysql::write')->create($sTransactionData);
            $msg = 'Successfully deposited funds into your Savings Account.';
        }

        return $msg;
    }

    public function voteToDisburse(Request $request){
        try{
            $request->validate([
                'user_id'=>'required',
                'account_id'=>'required|string',
                'disburse'=>'required|integer',
                'disburse_to'=>'required'
            ]);

            $userid = $request->user_id;
            $account = $request->account_id;
            $disburse = $request->disburse;
            $disburseTo = $request->disburse_to;

            $group = GroupSaving::on('mysql::read')->where([['member_id', $userid], ['id', $account]])->get();

            if(!$group){
                return response()->json(['message'=>'User is not a member of this Group Savings.'], 404);
            }

            $admin = GroupSaving::on('mysql::read')->where([['account_id', $account], ['member_id', $disburseTo], ['admin', 1]])->get()->first();

            if(!$admin){
                return response()->json(['message'=>'Only admins can be chosen to disburse funds to.'], 404);
            }

            GroupSaving::on('mysql::write')->where([['member_id', $userid], ['account_id', $account]])->update(['disburse'=>$disburse, 'disburse_to'=>$disburseTo]);

            if($disburse == 1){
                return response()->json(['message'=>'Successfully voted to disburse funds.'], 200);
            }else{
                return response()->json(['message'=>'Have not voted to disburse funds.'], 404);
            }
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function joinRequest(Request $request){
        try{
            $request->validate([
                'user_id'=>'required|uuid',
                'group_id'=>'required'
            ]);

            $userId = $request->user_id;
            $groupId = $request->group_id;

            $exists = JoinRequest::on('mysql::read')->where([['user_id', $userId], ['group_id', $groupId]])->first();
            
            if($exists || $exists!=null){
                return response()->json(['message'=>'Join Request already sent'], 422);
            }
            
            $alreadyMember = GroupSaving::on('mysql::read')->where([['account_id', $groupId],['member_id', $userId]])->first();

            if($alreadyMember)
            {
                return response()->json(['message'=>'User already belongs to this group.'], 422);
            }

            $user = User::on('mysql::read')->findOrFail($userId);

            $agent = Saving::on('mysql::read')->where([['id', $groupId], ['type', 'Group']])->get()->first();

            if(!$agent){
                return response()->json(['message'=>'Group Savings Account not found.'], 404);
            }

            $joinRequest = JoinRequest::on('mysql::write')->create([
                'user_id'=>$userId,
                'group_id'=>$groupId
            ]);

            if(!$joinRequest){
                return response()->json(['message'=>'Join request failed.'], 420);
            }

            return response()->json(['message'=>'Request Sent.', 'request'=>$joinRequest]);
        }catch (ModelNotFoundException $me){
            return response()->json(['message'=>'User not found'], 404);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function addUserToGroup(Request $request){
        try{
            $request->validate([
                'user_id'=>'required',
                'group_id'=>'required|string',
                'admin_id'=>'required',
                'accept_request'=>'required|boolean',
            ]);

            $userId = $request->user_id;
            $groupId = $request->group_id;
            $adminId = $request->admin_id;

            $admin = GroupSaving::on('mysql::read')->where([['account_id', $groupId], ['member_id', $adminId], ['admin', 1]])->get()->first();
            if(!$admin){
                return response()->json(['message'=>'Admin not found.'], 404);
            }
            $delReq = JoinRequest::on('mysql::write')->where([['user_id',$userId],['group_id',$groupId]])->get()->first();
            if(!$delReq){
                return response()->json(['message'=>'Join Request not found.'], 404);
            }
            
            //return $admin->account_id;
            if($request->accept_request){
               
                $accept = GroupSaving::on('mysql::write')->create([
                    'account_id'=>$groupId,
                    'member_id'=>$userId,
                    'admin'=>0,
                    'disburse'=>0,
                    'mid_payment'=>0,
                    'card_signature'=>'',
                    'disburse_to'=>'',
                ]);

                if(!$accept){
                    return response()->json(['message'=>'Could not accept request.'], 420);
                }

                $res = $delReq->delete();
                return response()->json(['message'=>'Request accepted.'], 200);
            }else{
                
                $res = $delReq->delete();
                return response()->json(['message'=>'Request rejected.'], 200);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function allJoinRequests($groupId){
        $requests = JoinRequest::on('mysql::read')->where('group_id', $groupId)->get();

        if(!$requests){
            return response()->json(['message'=>'Unable to retrieve requests.'], 404);
        }

        foreach($requests as $member){
            $user = User::on('mysql::read')->find($member->user_id);
            $member['name'] = $user->name;
            $member['image'] = $user->image;
        }

        return response()->json(['requests'=>$requests], 200);
    }

    public function allGroupMembers($groupId){
        try{
            $exists = Saving::on('mysql::read')->findOrFail($groupId);
            $members = GroupSaving::on('mysql::read')->where('account_id', $groupId)->get();

            if(!$members){
                return response()->json(['meesage'=>'Unable to fetch group members.'], 404);
            }

            foreach($members as $member){
                $user = User::on('mysql::read')->find($member->member_id);
                $member['name'] = $user->name;
                $member['image'] = $user->image;
            }
            return response()->json(['members'=>$members]);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Savings Account not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function allGroupAdmins($groupId){
        $admins = GroupSaving::on('mysql::read')->where([['account_id', $groupId],['admin', 1]])->get();

        if(!$admins){
            return response()->json(['message'=>'Unable to fetch group admins.'], 404);
        }
        return $admins;
    }

    public function allGroups(){
        $admins = Saving::on('mysql::read')->where([['type', 'Group'],['access', 'public']])->get();

        if(!$admins){
            return response()->json(['message'=>'Unable to fetch Groups.'], 404);
        }
        return $admins;
    }

    public function usersGroups($userId){
        try{
            $gdetails = array();
            $groups = GroupSaving::on('mysql::read')->where('member_id', $userId)->get();

            if(!$groups)
            {
                return response()->json(['message'=>'Unable to retrieve users groups.'], 404);
            }

            foreach($groups as $group){
                $details = Saving::on('mysql::read')->find($group->account_id);
                $gdetails[] = $details;
            }

            return response()->json(['groups'=>$gdetails]);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getAccountHistory($group_id){
        try{
            //$userId = $request->user_id;
            $id = $group_id;

            $accs = SavingTransaction::on('mysql::read')->where('savingsId', $id)->get();

            if(!$accs){
                return response()->json(['message'=>'Unable to retrieve Groups account history.'], 404);
            }
            return response()->json(['history'=>$accs], 200);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function getMemberHistory($group_id, $member_id){
        try{
            //$userId = $request->user_id;
            $id = $group_id;
            $userId = $member_id;

            $total = 0;

            $accs = SavingTransaction::on('mysql::read')->where([['savingsId', $id], ['userId', $userId]])->get();

            foreach($accs as $acc)
            {
                $total = $total + intval($acc->amount);
            }

            if(!$accs){
                return response()->json(['message'=>'Unable to retrieve Members account history.'], 404);
            }
            return response()->json(['history'=>$accs, 'totalContribution'=>$total], 200);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function addUserByPhoneNumber(Request $request){
        try{
            $request->validate([
                'admin_id'=>'required',
                'phone_number'=>'required',
                'group_id'=>'required'
            ]);

            $adminId = $request->admin_id;
            $phoneNumber = $request->phone_number;
            $groupId = $request->group_id;

            $admin = GroupSaving::on('mysql::read')->where([['account_id', $groupId], ['member_id', $adminId]])->get()->first();
            if(!$admin){
                return response()->json(['message'=>'Admin not found.'], 400);
            }

            if($admin->admin == 1) {
                $user = User::on('mysql::read')->where('phone', $phoneNumber)->get()->first();
                if(!$user){
                    return response()->json(['message'=>'User not found.'], 404);
                }

                $addToGroup = GroupSaving::on('mysql::write')->create([
                    'account_id'=>$groupId,
                    'member_id'=>$user->id,
                    'admin'=>0,
                    'disburse'=>0,
                    'card_signature'=>'',
                    'mid_payment'=>0,
                    'disburse_to'=>'', 
                ]);

                if(!$addToGroup){return response()->json(['message'=>'User not added to savings group.'], 420);}

                return response()->json(['message'=>'User added to group.'], 200);

            }else{
                return response()->json(['message'=>'You are not an admin of this group.'], 420);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function assignAdmin(Request $request){
        try{
            $request->validate([
                'admin_id'=>'required',
                'user_id'=>'required',
                'group_id'=>'required',
                'make_admin'=>'required'
            ]);

            $adminId = $request->admin_id;
            $userId = $request->user_id;
            $groupId = $request->group_id;
            $makeAdmin = $request->make_admin;

            $admin = GroupSaving::on('mysql::read')->where([['account_id', $groupId], ['member_id', $adminId]])->get()->first();
            if(!$admin){
                return response()->json(['message'=>'Admin not found.'], 404);
            }

            if($admin->admin == 1) {
                $user = User::on('mysql::read')->where('id', $userId)->get()->first();
                if(!$user){
                    return response()->json(['message'=>'User not found.']);
                }

                $updateUser = GroupSaving::on('mysql::write')->where([['account_id',$groupId], ['member_id', $userId]])->update(['admin'=>$makeAdmin]);

                if(!$updateUser){return response()->json(['message'=>'Unable to make user admin.'], 420);}

                return response()->json(['message'=>'User is now a group admin.'], 200);

            }else{
                return response()->json(['message'=>'You are not an admin of this group.'], 420);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function countTransactions(){
        /*$count = SavingTransaction::where('amount_deposited', '1000')->count();
        $tot = SavingTransaction::all()->count();*/

        $tot = GroupSaving::on('mysql::read')->where('account_id', 'da21cd81-2907-4f7b-a8a3-e1f52da2c6b9')->count();
        $count = GroupSaving::on('mysql::read')->where([['account_id', 'da21cd81-2907-4f7b-a8a3-e1f52da2c6b9'], ['disburse', '1']])->count();
        $wal = Wallet::on('mysql::read')->where('user_id', 1)->get();

        $hoc = SavingTransaction::select(DB::raw('savingsId, count(savingsId) as most_freq'))
            ->groupBy('savingsId')
            ->orderBy('savingsId', 'DESC')
            ->get()
            ->first();

        //return $wal;

        $per = 100 * ($count /  $tot);
        //$per = GroupSaving::on('mysql::read')->where('account_id', 'da21cd81-2907-4f7b-a8a3-e1f52da2c6b9')->count();
        /*if($per >= 70){
            return 'Greate or equal to seventy percent'. $per;
        }elseif ($per<70 && $per>20){
            return 'Between 20 to 69 '. $per;
        }else{
            return 'Below 20 percent '. $per;
        }*/

        return $hoc;
    }

    public function disburseSavings(Request $request){
        try {
            $request->validate([
                'group_id'=>'required',
                'admin_id'=>'required'
            ]);

            $groupId = $request->group_id;
            $adminId = $request->admin_id;


            $tot = GroupSaving::on('mysql::read')->where('account_id', $groupId)->count();
            $count = GroupSaving::on('mysql::read')->where([['account_id', $groupId], ['disburse', '1']])->count();

            $per = 100 * ($count / $tot);
            //$per = GroupSaving::where('account_id', 'da21cd81-2907-4f7b-a8a3-e1f52da2c6b9')->count();
            if ($per >= 70) {

                //Check admin with highest votes to disburse to.
                $highestVote = GroupSaving::select(DB::raw('disburse_to, count(disburse_to) as most_freq'))
                    ->groupBy('disburse_to')
                    ->orderBy('disburse_to', 'DESC')
                    ->get()
                    ->first();

                //disburse the savings into the admins wallet.
                $group = Saving::on('mysql::write')->find($groupId);
                if(!$group) {
                    return response()->json(['message' => 'Unable to retrieve Savings Group.'], 404);
                }

                $groupBalance = intval($group->balance);

                $admin = GroupSaving::on('mysql::read')->where([['member_id', $adminId], ['admin', 1], ['account_id', $groupId]])->get()->first();

                if(!$admin){
                    return response()->json(['message'=>'Unable to find Admin.'], 404);
                }

                $adminWallet = Wallet::on('mysql::write')->where('user_id', $highestVote->disburse_to)->get()->first();

                if(!$adminWallet){
                    return response()->json(['message'=>'Unable to retrieve admin wallet.'], 404);
                }

                $today = date('Y-m-d');
                $endDate = $group->end_date;
                $newWalletBalance = 0;
                
                $todayTime = strtotime($today);
                $endtime = strtotime($endDate);
                if($endtime > $todayTime){
                    $newBalance = $groupBalance - ($groupBalance * $group->penalty) + intval($adminWallet->balance);
                }else{
                    $newBalance = $groupBalance + intval($adminWallet->balance) + ($groupBalance * $group->interest_rate);
                }
                $adminWallet->update(['balance'=>$newBalance]);
                $group->update(['balance'=>0]);
                //return $admin;
                $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $adminWallet->user->id], ['account_name', 'Wallet ID']])->first();
                //Add transaction to Savings Transactions table
                SavingTransaction::on('mysql::write')->create([
                    'savingsId'=>$groupId,
                    'userId'=>$admin->member_id,
                    'amount'=>$groupBalance,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Disbursement'
                ]);

                //Add transaction to Wallet Transaction
                WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=>$adminWallet->id,
                    'type'=>'Credit',
                    'amount'=>$groupBalance,
                    'description'=>'Savings Account Disbursement',
                    'receiver_account_number'=>$acc->account_number,
                    'receiver_name'=>$adminWallet->user->name,
                    'transfer'=>false,
                    'status'=>'success',
                ]);

                return response()->json(['message'=>'Successfully disbursed Group Savings into Admin wallet.'], 200);
            }/* elseif ($per < 70 && $per > 20) {
                return 'Between 20 to 69 ' . $per;
            } else {
                return 'Below 20 percent ' . $per;
            }*/
        }catch(ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }
    }

    public function voteCount($group_id){
        try{
            $totalVotes = 0;
            $groupAdmins = GroupSaving::on('mysql::read')->where([['account_id', $group_id], ['admin', 1]])->get();

            foreach($groupAdmins as $admin){
                $voteCount = GroupSaving::on('mysql::read')->where([['account_id', $group_id], ['disburse_to', $admin->member_id]])->count();
                $user = User::on('mysql::read')->find($admin->member_id);
                $admin['votes'] = $voteCount;
                $admin['name'] = $user->name;
                $totalVotes = $totalVotes + $voteCount;
            }

            return response()->json(['message'=>'Vote count retrieved sucessfully.', 'votes'=>$groupAdmins, 'total_votes'=>$totalVotes]);

        }catch(Exception $e){
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'Power Controller - Get meter info method (api.transave.com.ng) ',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            return response()->json(['message'=>$e->getMessage()], 420);
        }
    }
}
