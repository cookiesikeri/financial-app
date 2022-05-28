<?php

namespace App\Http\Controllers\Savings;

use App\Models\AgentSavings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\AccountNumber;
use App\Models\BreakSaving;
use App\Models\JoinRequest;
use App\Models\PaystackRefRecord;
use App\Models\SavedCard;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\isEmpty;

class AgentSavingsController extends Controller
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
                'admin_setup'=>'required|boolean',
                'name'=>'exclude_unless:admin_setup,true|required|string',
                'description'=>'exclude_unless:admin_setup,true|required|string',
                'amount'=>'exclude_unless:admin_setup,true|required|integer|numeric|gt:0',
                'start_date'=>'exclude_unless:admin_setup,true|required',
                'end_date'=>'exclude_unless:admin_setup,true|required',
                'num_of_members'=>'exclude_unless:admin_setup,true|required|numeric|integer',
                'user_id'=>'required|uuid',
                'cycle'=>'exclude_unless:admin_setup,true|required|string',
                'duration'=>'exclude_unless:admin_setup,true|required',
            ]);

            //Get user and check if User exists
            $user = User::on('mysql::read')->findOrFail($request->user_id);
            
            if(!$user){
                return response()->json(['error'=>'Unable to find User'], 404);
            }

            if($request->admin_setup){
                $startDate = date('Y-m-d', strtotime($request->start_date));
                $endDate = date('Y-m-d', strtotime($request->end_date));

                $nextSave = '';
                if(strtoupper($request->cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($request->cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($request->cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }
                
                $nextDate = date('Y-m-d', strtotime($startDate.$nextSave));

                $data = array(
                    'userId' => $request->user_id,
                    'amount'=>$request->amount,
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'cycle'=>$request->cycle,
                    'access'=>'public',
                    'balance'=>0,
                    'status'=>'active',
                    'end_date'=>$endDate,
                    'start_save'=>$startDate,
                    'next_save'=>$nextDate,
                    'projected_saving'=>0,
                    'type'=>'Agent',
                    'card_signature'=>'',
                    'num_of_members'=>$request->num_of_members,
                    'admin_setup'=>1,
                );
    
                //return $data;
    
                $savings = Saving::on('mysql::write')->create($data);
    
                return response()->json(['message'=>'Agent Savings Account created.', 'account'=>$savings]);
            }else{
                $savings = Saving::on('mysql::write')->create([
                    'amount'=>0,
                    'userId'=>$request->user_id,
                    'type'=>'Agent',
                    'access'=>'public',
                    'admin_setup'=>0,
                ]);

                return response()->json(['message'=>'Agent Savings Account created.', 'account'=>$savings]);
            }


        
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'User not found.'], 404);
        }
        catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }
        catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function joinRequest(Request $request){
        try{
            $request->validate([
                'user_id'=>'required|uuid',
                'group_id'=>'required|uuid'
            ]);

            $userId = $request->user_id;
            $groupId = $request->group_id;

            $exists = JoinRequest::on('mysql::read')->where([['user_id', $userId], ['group_id', $groupId]])->first();

            if($exists || $exists!=null){
                return response()->json(['message'=>'Join Request already sent'], 422);
            }

            $alreadyMember = AgentSavings::on('mysql::read')->where([['account_id', $groupId],['user_id', $userId]])->first();

            if($alreadyMember)
            {
                return response()->json(['message'=>'User already belongs to this group.'], 422);
            }

            $user = User::on('mysql::read')->findOrFail($userId);

            $agent = Saving::on('mysql::read')->where([['id', $groupId], ['type', 'Agent']])->get()->first();

            if(!$agent){
                return response()->json(['message'=>'Agent Savings Account not found.'], 404);
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

    public function allJoinRequests($groupId){

        //return $groupId;
        try{
            $requests = JoinRequest::on('mysql::read')->where('group_id', $groupId)->get();

            if(!$requests){
                return response()->json(['message'=>'Unable to retrieve requests.'], 404);
            }

            if(!$requests->count() || $requests == null){
                return response()->json(['message'=>'No join requests at the moment.', 'requests'=>$requests], 404);
            }

            foreach($requests as $member){
                $user = User::on('mysql::read')->find($member->user_id);
                $member['name'] = $user->name;
                $member['image'] = $user->image;
            }

            return response()->json(['requests'=>$requests], 200);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function allGroupMembers($groupId){
        try{

            $exists = Saving::on('mysql::read')->findOrFail($groupId);

            $members = AgentSavings::on('mysql::read')->where('account_id', $groupId)->get();

            if(!$members){
                return response()->json(['meesage'=>'Unable to fetch group members.'], 404);
            }

            foreach($members as $member){
                $user = User::on('mysql::read')->find($member->user_id);
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

    public function getMember(Request $request){
        try{
            $request->validate([
                'user_id'=>'required|uuid',
                'group_id'=>'required|uuid'
            ]);

            $userId = $request->user_id;
            $groupId = $request->group_id;

            $user = User::on('mysql::read')->findOrFail($userId);

            $exists = Saving::on('mysql::read')->findOrFail($groupId);

            $member = AgentSavings::on('mysql::read')->where([['account_id', $groupId], ['user_id', $userId]])->get()->first();

            if(!$member){
                return response()->json(['message'=>'Users Agent Savings Account not found.'], 404);
            }

            return response()->json(['message'=>'Member retrieved.', 'member'=>$member]);
        }catch (ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function allGroups(){
        try{
            $groups = Saving::on('mysql::read')->where([['type', 'Agent'],['access', 'public']])->get();

            if(!$groups){
                return response()->json(['message'=>'Unable to fetch Groups.'], 404);
            }
            return response()->json(['groups'=>$groups]);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getAccountHistory($groupId){
        try{
            $exists = Saving::on('mysql::read')->findOrFail($groupId);

            $accs = SavingTransaction::on('mysql::read')->where('savingsId', $groupId)->get();

            if(!$accs){
                return response()->json(['message'=>'Unable to retrieve Groups account history.'], 404);
            }
            return response()->json(['history'=>$accs], 200);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch (ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getCommAccountHistory($groupId){
        try{
            $res = array();
            $exists = Saving::on('mysql::read')->findOrFail($groupId);

            $accs = SavingTransaction::on('mysql::read')->where('savingsId', $groupId)->get();

            if(!$accs){
                return response()->json(['message'=>'Unable to retrieve Groups account history.'], 404);
            }

            foreach($accs as $acc){
                $user = User::on('mysql::read')->findOrFail($acc->userId);
                $acc['name'] = $user->name;
                $res[] = $acc; 
            }
            return response()->json(['history'=>$res], 200);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch (ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function getMemberHistory(Request $request){
        try{
            $request->validate([
                'account_id'=>'required',
                'user_id'=>'required'
            ]);

            //$userId = $request->user_id;
            $id = $request->account_id;
            $userId = $request->user_id;

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
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function fundSavingsAccountFromWallet(Request $request){
        try{
            $request->validate([
                'account_id'=>'required',
                'user_id'=>'required',
                'amount'=>'required|numeric|gt:0',
            ]);
            $amount = $request->amount;
            //$userId = $request->user_id;
            $userId = Auth::id();
            $savings = Saving::on('mysql::write')->findOrFail($request->account_id);

            if(!$savings){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            /*if($savings->userId != $request->user_id){
                return response()->json(['error'=>'You\'re trying to deposit in another users Savings Account.']);
            }*/

            //$member = GroupSaving::where('member_id', $userId)->get();

            $walletFunded = $this->walletFunding($savings, $userId, $amount);
            //'account'=>$savings,
            if(!$walletFunded['error']){
                return response()->json(['account'=>$savings,'message'=>$walletFunded['message']], 200);
            }else{
                return response()->json(['message'=>$walletFunded['message']], $walletFunded['error_code']);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function walletFunding($savings, $userId, $amount){
        $msg = '';
        $user = User::on('mysql::write')->find($userId);
        $member = AgentSavings::on('mysql::write')->where([['user_id', $userId],['account_id', $savings->id]])->get()->first();

        if(!$user){
            return array('error'=>true, 'message'=>'Unable to retrieve User.', 'error_code'=>404);
        }

        if(!$member){
            return array('message'=>'User is not a member of this Agent Savings.', 'error'=>true, 'error_code'=>422);
        }
        //If wallet has insufficient funds
        $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
        $walTr = WalletTransaction::on('mysql::write')->create([
            'wallet_id'=> $user->wallet->id,
            'type'=>'Debit',
            'amount'=>$amount,
            'description'=>'Savings Account Deposit',
            'receiver_account_number'=>$acc->account_number,
            'receiver_name'=>$user->name,
            'transfer'=>false,
            'transaction_type'=>'wallet',
        ]);

        if($user->wallet->balance < $amount || $amount <= 0){
            $msg = 'Insufficient funds. Please fund your wallet or use a debit/credit card to deposit into your Savings Account.';
            $walTr->update([
                'status'=>'failed',
            ]);
            return array('message'=>$msg, 'error'=>true, 'error_code'=>422);
        }
        //Users wallet balance is sufficient
        elseif ($user->wallet->balance >= $amount){
            //Debit wallet
            $currentBalance = floatval($user->wallet->balance);
            $amount = floatval($amount);
            $depositAmount = floatval($member->balance) + $amount;
            $totalSavings = floatval($savings->balance) + $amount;
            $perc = $amount * $savings->commission_percent;
            $commission = floatval($savings->commission_balance) + ($amount * $savings->commission_percent);
            $newBalance = $currentBalance - $amount;

            if($savings->admin_setup){
                $cycle = $savings->cycle;
                $currentNextSave = $savings->next_save;
            }else{
                $cycle = $member->cycle;
                $currentNextSave = $member->next_save;
            }
            //$currentNextSave = $savings->next_save;
            $nextSave = '';
            if(strtoupper($cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }
            //return $newBalance; date('Y-m-d', strtotime($startDate.$nextSave)) //date_add(date_create(), date_interval_create_from_date_string($nextSave))
            if($currentNextSave == null) {
                //Update savings account balance
                $member->update([
                    'balance' => $depositAmount,
                    'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                ]);
                $savings->update(['balance' => $totalSavings,
                'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave)), 'commission_balance'=>$commission]);
            }else{
                $today = date('Y-m-d');
                $nextDate = $currentNextSave;

                $todaysDate = strtotime($today);
                $nextSaveDate = strtotime($nextDate);

                if($nextSaveDate > $todaysDate){
                    $member->update(['mid_payment'=>1, 'balance'=>$depositAmount]);
                    $savings->update(['balance'=>$totalSavings, 'commission_balance'=>$commission]);
                }elseif ($nextSaveDate == $todaysDate){
                    if($savings->admin_setup){
                        $member->update(['balance'=>$depositAmount]);
                        $savings->update([
                            'balance'=>$totalSavings,
                            'commission_balance'=>$commission,
                            'next_save'=>date('Y-m-d',strtotime($currentNextSave.$nextSave)),
                        ]);
                    }else{
                        $member->update(['balance'=>$depositAmount, 'next_save'=>date('Y-m-d',strtotime($currentNextSave.$nextSave))]);
                        $savings->update(['balance'=>$totalSavings, 'commission_balance'=>$commission]);
                    }
                }

                /* Saving::where('id', $savings->id)->update([
                    'balance' => $depositAmount
                ]); */
            }
            //Update wallet balance
            $user->wallet()->update(['balance'=>$newBalance]);
            
            $walTr->update([
                'status'=>'success',
            ]);
            //Add to savings transaction table
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$userId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d'),
                'type'=>'Deposit',
                'commission_percent'=>$savings->commission_percent,
                'commission_paid'=>$perc,
            );
            //$this->savingsTransaction->save($sTransactionData);
            SavingTransaction::on('mysql::write')->create($sTransactionData);
            $msg = 'Successfully deposited funds into your Savings Account.';
            return array('message'=>$msg, 'error'=>false, 'error_code'=>200);
        }

    }

    public function updateMemberSetup(Request $request){
        try{
            $request->validate([
                'account_id'=>'required|uuid',
                'user_id'=>'required|uuid',
                'amount'=>'required|integer|numeric',
                'cycle'=>'required',
                'duration'=>'required',
                'overall'=>'required|integer|numeric',
                'start_date'=>'required',
                'end_date'=>'required',
            ]);

            $user = User::on('mysql::read')->findOrFail($request->user_id);

            $agent = Saving::on('mysql::read')->findOrFail($request->account_id);

            if($agent->admin_setup){
                return response()->json(['message'=>'Admin setup cannot be overwritten.'], 420);
            }

            $account = AgentSavings::on('mysql::write')->where([['account_id', $request->account_id], ['user_id', $request->user_id]])->get()->first();

            if(!$account){
                return response()->json(['message'=>'Users account not found.']);
            }

            $startDate = date('Y-m-d',strtotime($request->start_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));

            $account->update([
                'amount'=>$request->amount,
                'cycle'=>$request->cycle,
                'duration'=>$request->duration,
                'projected_saving'=>$request->overall,
                'start_date'=>$startDate,
                'end_date'=>$endDate,
            ]);

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }
        catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()],422);
        }
        catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function addUser(Request $request){
        try{
            //uuid|unique:agent_savings,user_id
            $request->validate([
                'account_id'=>'required|uuid',
                'phone_number'=>'required|numeric',
                'amount'=>'required|integer|numeric',
                'cycle'=>'required',
                'duration'=>'required',
                'overall'=>'required|integer|numeric',
                'start_date'=>'required',
                'end_date'=>'required',
                'description'=>'required',
            ]);

            $user = User::on('mysql::read')->where('phone',$request->phone_number)->first();

            if(!$user){
                return response()->json(['message'=>'User not found.'], 404);
            }

            $agent = Saving::on('mysql::read')->findOrFail($request->account_id);

            if($agent->admin_setup){

                $data = array(
                    'account_id'=>$request->account_id,
                    'user_id' => $user->id,
                    'amount'=>0,
                    'balance'=>0,
                );

                $member = AgentSavings::on('mysql::write')->create($data);
                if(!$member){
                    return response()->json(['message'=>'Unable to add User to Agent Savings Group.'], 420);
                }
                return response()->json(['message'=>'User added to Agent Savings Group successfully.', 'member'=>$member]);
            }else{
                $startDate = date('Y-m-d', strtotime($request->start_date));
                $endDate = date('Y-m-d', strtotime($request->end_date));
                $nextSave = '';
                if(strtoupper($request->cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($request->cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($request->cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }
                
                $nextDate = date('Y-m-d', strtotime($startDate.$nextSave));

                $data = array(
                    'account_id'=>$request->account_id,
                    'user_id' => $user->id,
                    'amount'=>$request->amount,
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'cycle'=>$request->cycle,
                    'balance'=>0,
                    'end_date'=>$endDate,
                    'start_save'=>$startDate,
                    'next_save'=>$nextDate,
                    'projected_saving'=>0,
                    'card_signature'=>'',
                );

                $member = AgentSavings::on('mysql::write')->create($data);
                if(!$member){
                    return response()->json(['message'=>'Unable to add User to Agent Savings Group.'], 420);
                }

                return response()->json(['message'=>'User added to Agent Savings Group successfully.', 'member'=>$member]);
            }

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }
        catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()],422);
        }
        catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function addUserToGroup(Request $request){
        try{
            $request->validate([
                'user_id'=>'required|uuid',
                'group_id'=>'required|uuid',
                'agent_id'=>'required|uuid',
                'accept_request'=>'required|boolean',
            ]);

            $userId = $request->user_id;
            $groupId = $request->group_id;
            $agentId = $request->agent_id;

            $acc = Saving::on('mysql::read')->findOrFail($groupId);
            
            if($acc->userId != $agentId){
                return response()->json(['message'=>'This Agent did not create the group.'], 404);
            }
            
            $delReq = JoinRequest::on('mysql::write')->where([['user_id',$userId],['group_id',$groupId]])->get()->first();

            if(!$delReq){
                return response()->json(['message'=>'Join Request not found.'], 404);
            }

            if($request->accept_request){
                if($acc->admin_setup) {
                    
                    //return $admin->account_id;
                    $accept = AgentSavings::on('mysql::write')->create([
                        'account_id'=>$groupId,
                        'user_id' => $userId,
                        'amount'=>0,
                        'balance'=>0,
                    ]);

                    if(!$accept){
                        return response()->json(['message'=>'Could not accept request.'], 420);
                    }

                    $delReq->delete();
                    return response()->json(['message'=>'Request accepted.', 'member'=>$accept], 200);

                }else{
                    $data = array(
                        'account_id'=>$acc->id,
                        'user_id' => $userId,
                        'amount'=>$acc->amount,
                        'name'=>$acc->name,
                        'description'=>$acc->description,
                        'cycle'=>$acc->cycle,
                        'balance'=>0,
                        'end_date'=>$acc->end_date,
                        'start_save'=>$acc->start_date,
                        'next_save'=>$acc->next_save,
                        'projected_saving'=>0,
                        'card_signature'=>'',
                    );

                    $member = AgentSavings::on('mysql::write')->create($data);
                    if(!$member){
                        return response()->json(['message'=>'Could not accept request.'], 420);
                    }

                    $delReq->delete();
                    return response()->json(['message'=>'Request Accepted.', 'member'=>$member]);
                }
            }else{
                    
                $res = $delReq->delete();
                return response()->json(['message'=>'Request rejected.'], 200);
            }

        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>'Savings Account not found.'], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function agentFundUserFromWallet(Request $request){
        try{
            $request->validate([
                'account_id'=>'required|uuid',
                'user_id'=>'required|uuid',
                'agent_id'=>'required|uuid',
                'amount'=>'required|integer|numeric|gt:0',
            ]);
            $amount = intval($request->amount);
            $userId = $request->user_id;
            $agentId = $request->agent_id;
            $savings = Saving::on('mysql::write')->findOrFail($request->account_id);

            $agent = User::on('mysql::write')->findOrFail($agentId);

            if(!$savings){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            if($agentId != $savings->userId){
                return response()->json(['message'=>'Agent is not owner of this account. Check Agent ID.'], 420);
            }

            $member = AgentSavings::on('mysql::write')->where([['account_id', $savings->id], ['user_id', $userId]])->get()->first();

            if(!$member){
                return response()->json(['message'=>'User is not a member of this agent savings group.'], 404);
            }

            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $agent->wallet->id], ['account_name', 'Wallet ID']])->first();

            $walTr = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=> $agent->wallet->id,
                'type'=>'Debit',
                'amount'=>$amount,
                'description'=>'Savings Account Deposit',
                'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$agent->name,
                'transfer'=>false,
                'transaction_type'=>'wallet',
            ]);

            //If wallet has insufficient funds
            if($agent->wallet->balance < $amount || $amount <= 0){
                $walTr->update([
                    'status'=>'failed',
                ]);
                $msg = 'Insufficient funds. Please fund your wallet or use a debit/credit card to deposit into your Savings Account.';
            }
            //Users wallet balance is sufficient
            elseif ($agent->wallet->balance >= $amount){
                //Debit wallet
                $currentBalance = intval($agent->wallet->balance);
                //$amount = intval($amount);
                $depositAmount = intval($member->balance) + $amount;
                $totalSavings = intval($savings->balance) + $amount;
                $newBalance = $currentBalance - $amount;
                $perc = $amount * $savings->commission_percent;
                $commission = intval($savings->commission_balance) + ($amount * $savings->commission_percent);

                if($savings->admin_setup){
                    $cycle = $savings->cycle;
                    $currentNextSave = $savings->next_save;
                }else{
                    $cycle = $member->cycle;
                    $currentNextSave = $member->next_save;
                }
                //$currentNextSave = $savings->next_save;
                $nextSave = '';
                if(strtoupper($cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }
                //return $newBalance; date('Y-m-d', strtotime($startDate.$nextSave)) //date_add(date_create(), date_interval_create_from_date_string($nextSave))
                if($currentNextSave == null) {
                    //Update savings account balance
                    $member->update([
                        'balance' => $depositAmount,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                    ]);
                    $savings->update([
                        'balance'=>$totalSavings,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave)),
                        'commission_balance'=>$commission,
                    ]);
                }else{
                    $today = date('Y-m-d');
                    $nextDate = $currentNextSave;

                    $todaysDate = strtotime($today);
                    $nextSaveDate = strtotime($nextDate);

                    if($nextSaveDate > $todaysDate){
                        $member->update(['mid_payment'=>1, 'balance'=>$depositAmount]);
                        $savings->update(['balance'=>$totalSavings, 'commission_balance'=>$commission]);
                    }elseif ($nextSaveDate == $todaysDate){
                        if($savings->admin_setup){
                            $member->update(['balance'=>$depositAmount]);
                            $savings->update([
                                'balance'=>$totalSavings,
                                'commission_balance'=>$commission,
                                'next_save'=>date('Y-m-d',strtotime($currentNextSave.$nextSave)),
                            ]);
                        }else{
                            $member->update(['balance'=>$depositAmount, 'next_save'=>date('Y-m-d',strtotime($currentNextSave.$nextSave))]);
                            $savings->update(['balance'=>$totalSavings, 'commission_balance'=>$commission]);
                        }
                    }

                    /* Saving::where('id', $savings->id)->update([
                        'balance' => $depositAmount
                    ]); */
                }
                //Update wallet balance
                $agent->wallet()->update(['balance'=>$newBalance]);

                $walTr->update([
                    'status'=>'success',
                ]);
                //Add to savings transaction table
                $sTransactionData = array(
                    'savingsId'=>$savings->id,
                    'userId'=>$agentId,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d'),
                    'type'=>'Deposit',
                    'commission_percent'=>$savings->commission_percent,
                    'commission_paid'=>$perc,
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);
                $msg = 'Successfully deposited funds into your Savings Account.';
            }
            /*if($savings->userId != $request->user_id){
                return response()->json(['error'=>'You\'re trying to deposit in another users Savings Account.']);
            }*/

            //$member = GroupSaving::where('member_id', $userId)->get();

            $walletFunded = '';
            //'account'=>$savings,
            return response()->json(['account'=>$savings,'message'=>$msg], 200);

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch (ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function fundSavingsAccountFromCard(Request $request){
        try{
            $request->validate([
                'payment_ref'=>'required',
                'account_id'=>'required|uuid',
                'user_id'=>'required|uuid',
                'save_card'=>'required|boolean'
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

            $savings =  Saving::on('mysql::write')->findOrFail($savingsId);

            if(!$savings){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            $member = AgentSavings::on('mysql::write')->where([['account_id', $savingsId], ['user_id', $userId]])->get()->first();

            //return $member;

            if(!$member){
                return response()->json(['error'=>'User is not a member of this Agent Savings.'], 404);
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
                if($savings->admin_setup){
                    $currNextSave = $savings->next_save;
                    $cycle = $savings->cycle;
                }else{
                    $currNextSave = $member->next_save;
                    $cycle = $member->cycle;
                }
                $newBalance = intval($savings->balance) + $amount;
                $memBalance = intval($member->balance) + $amount;
                $perc = $amount * $savings->commission_percent;
                $commission = intval($savings->commission_balance) + ($amount * $savings->commission_percent);
                $today = date('Y-m-d');

                $todaysDate = strtotime($today);
                $nextDate = strtotime($currNextSave);
                $nextSave = '';
                if(strtoupper($cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }

                if($currNextSave == null){
                    //Update savings account balance 'balance' => $depositAmount,
                    //'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                    $member->update([
                        'balance' => $memBalance,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                    ]);
                    $savings->update([
                        'balance'=>$newBalance,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave)),
                        'commission_balance'=>$commission,
                    ]);
                }else {
                    //Check if it is a payment before the next due date
                    if ($nextDate > $todaysDate) {
                        $member->update(['mid_payment'=>1, 'balance'=>$memBalance]);
                        $savings->update(['balance'=>$newBalance, 'commission_balance'=>$commission]);
                    } elseif ($nextDate == $todaysDate) {
                        
                        if($savings->admin_setup){
                            $member->update(['balance'=>$memBalance]);
                            $savings->update([
                                'balance'=>$newBalance,
                                'commission_balance'=>$commission,
                                'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave)),
                            ]);
                        }else{
                            $member->update(['balance'=>$memBalance, 'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave))]);
                            $savings->update(['balance'=>$newBalance, 'commission_balance'=>$commission]);
                        }
                    }
                
                }
                //Record transaction in Savings Transaction
                $sTransactionData = array(
                    'savingsId'=>$savings->id,
                    'userId'=>$userId,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Deposit',
                    'commission_percent'=>$savings->commission_percent,
                    'commission_paid'=>$perc,
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);
                $checkRef->update(['status'=>'success']);
                //If User wants to save card for auto payment, Store card details
                if($request->save_card == 1 ){
                    $cardExists = SavedCard::where('signature', $card['signature'])->exists();

                    if(!$cardExists){                        
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
                            'user_id'=>$member->user_id,
                            'user_email'=>''
                        );

                        $isSaved = SavedCard::on('mysql::write')->create($cardDets);

                        //$member->update(['card_signature'=>$card['signature']]);

                        if($isSaved){
                            $cardMsg = true;
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
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function agentFundSavingsAccountFromCard(Request $request){
        try{
            $request->validate([
                'payment_ref'=>'required',
                'account_id'=>'required',
                'user_id'=>'required',
                'agent_id'=>'required',
                'save_card'=>'required'
            ]);

            $savingsId = $request->account_id;
            $userId = $request->user_id;
            $agentId = $request->agent_id;
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

            $savings =  Saving::on('mysql::write')->findOrFail($savingsId);

            $agent = User::on('mysql::read')->findOrFail($agentId);

            if(!$savings){
                return response()->json(['error'=>'Unable to retrieve account.'], 404);
            }

            if($agent->id != $savings->userId){
                return response()->json(['message'=>'Agent not admin of this group.']);
            }

            $member = AgentSavings::on('mysql::write')->where([['account_id', $savingsId], ['user_id', $userId]])->get()->first();

            //return $member;

            if(!$member){
                return response()->json(['error'=>'User is not a member of this Agent Savings.'], 404);
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
                if($savings->admin_setup){
                    $currNextSave = $savings->next_save;
                    $cycle = $savings->cycle;
                }else{
                    $currNextSave = $member->next_save;
                    $cycle = $member->cycle;
                }
                $newBalance = intval($savings->balance) + $amount;
                $memBalance = intval($member->balance) + $amount;
                $perc = $amount * $savings->commission_percent;
                $commission = intval($savings->commission_balance) + ($amount * $savings->commission_percent);
                $today = date('Y-m-d');

                $todaysDate = strtotime($today);
                $nextDate = strtotime($currNextSave);
                $nextSave = '';
                if(strtoupper($cycle) == 'DAILY'){
                    $nextSave = '1 day';
                }elseif (strtoupper($cycle) == 'WEEKLY'){
                    $nextSave = '1 week';
                }elseif (strtoupper($cycle) == 'MONTHLY'){
                    $nextSave = '1 month';
                }

                if($currNextSave == null){
                    //Update savings account balance 'balance' => $depositAmount,
                    //'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                    $member->update([
                        'balance' => $memBalance,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave))
                    ]);
                    $savings->update([
                        'balance'=>$newBalance,
                        'next_save' => date('Y-m-d', strtotime(date('Y-m-d').$nextSave)),
                        'commission_balance'=>$commission,
                    ]);
                }else {
                    //Check if it is a payment before the next due date
                    if ($nextDate > $todaysDate) {
                        $member->update(['mid_payment'=>1, 'balance'=>$memBalance]);
                        $savings->update(['balance'=>$newBalance, 'commission_balance'=>$commission]);
                    } elseif ($nextDate == $todaysDate) {
                        if($savings->admin_setup){
                            $member->update(['balance'=>$memBalance]);
                            $savings->update([
                                'balance'=>$newBalance,
                                'commission_balance'=>$commission,
                                'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave)),
                            ]);
                        }else{
                            $member->update(['balance'=>$memBalance, 'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave))]);
                            $savings->update(['balance'=>$newBalance, 'commission_balance'=>$commission]);
                        }
                    }
                
                }
                //Record transaction in Savings Transaction
                $sTransactionData = array(
                    'savingsId'=>$savings->id,
                    'userId'=>$userId,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Deposit',
                    'commission_percent'=>$savings->commission_percent,
                    'commission_paid'=>$perc,
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);
                $checkRef->update(['status'=>'success']);
                //If User wants to save card for auto payment, Store card details
                if($request->save_card == 1 ){
                    $cardExists = SavedCard::where('signature', $card['signature'])->exists();

                    if(!$cardExists){                        
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
                            'user_id'=>$agent->id,
                            'user_email'=>''
                        );

                        $isSaved = SavedCard::on('mysql::write')->create($cardDets);

                        //$savings->update(['card_signature'=>$card['signature']]);

                        if($isSaved){
                            $cardMsg = true;
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
            return response()->json(['message'=>$me->getMessage()], 404);
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

        try{
            $savings = Saving::on('mysql::write')->findOrFail($request->account_id);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Savings Account not found.'], 404);
        }
        
        $account = AgentSavings::on('mysql::write')->where([['account_id', $request->account_id], ['user_id', $user->id]])->get()->first();

        if(!$account){
            return response()->json(['message'=>'User not member of this Agent Savings Group'], 420);
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
            //return $user->wallet->id;
            if($savings->admin_setup){
                $currNextSave = $savings->next_save;
                $cycle = $savings->cycle;
            }else{
                $currNextSave = $account->next_save;
                $cycle = $account->cycle;
            }
            $today = date('Y-m-d');
            $todaysDate = strtotime($today);
            $nextDate = strtotime($currNextSave);
            $nextSave = '';
            if(strtoupper($cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }
            //$this->user->update_user_wallet_balance(($user->wallet->balance + $amount));
            $newBal = intval($account->balance) + intval($amount);
            $totalBal = intval($savings->balance) + intval($amount);
            $perc = $amount * $savings->commission_percent;
            $commission = intval($savings->commission_balance) + ($amount * $savings->commission_percent);
            if ($nextDate > $todaysDate) {
                $account->update(['mid_payment'=>1, 'balance'=>$newBal]);
                $savings->update(['balance'=>$totalBal, 'commission_balance'=>$commission]);
            } elseif ($nextDate == $todaysDate) {
                if($savings->admin_setup){
                    $account->update(['balance'=>$newBal]);
                    $savings->update([
                        'balance'=>$totalBal,
                        'commission_balance'=>$commission,
                        'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave)),
                    ]);
                }else{
                    $account->update(['balance'=>$newBal, 'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave))]);
                    $savings->update(['balance'=>$totalBal, 'commission_balance'=>$commission]);
                }                
            }
            
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$userId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Deposit',
                'commission_percent'=>$savings->commission_percent,
                'commission_paid'=>$perc,
            );
            $walTransaction = SavingTransaction::on('mysql::write')->create($sTransactionData);
        }

        return response()->json(['message'=>$msg]);
    }

    public function agent_fund_user_wallet_transfer(Request $request)
    {
        try{
            $request->validate([
                'reference'=>'required',
                'user_id'=>'required',
                'account_id'=>'required',
                'agent_id'=>'required',
            ]);
        }catch(ValidationException $exception){
            return response()->json(['errors'=>$exception->errors(), 'message'=>$exception->getMessage()]);
        }
        $paystack_payment_reference = $request->reference;
        //$amount = $request->amount;
        $userId = $request->user_id;
        $agentId = $request->agent_id;
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

        $agent = User::on('mysql::read')->findOrFail($agentId);

        if(!$savings){
            return response()->json(['error'=>'Unable to retrieve account.'], 404);
        }

        if($agent->id != $savings->userId){
            return response()->json(['message'=>'Agent not admin of this group.']);
        }
        
        $account = AgentSavings::on('mysql::write')->where([['account_id', $request->account_id], ['user_id', $user->id]])->get()->first();

        if(!$account){
            return response()->json(['message'=>'User not member of this Agent Savings Group'], 420);
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
            //return $user->wallet->id;
            if($savings->admin_setup){
                $currNextSave = $savings->next_save;
                $cycle = $savings->cycle;
            }else{
                $currNextSave = $account->next_save;
                $cycle = $account->cycle;
            }
            $today = date('Y-m-d');
            $todaysDate = strtotime($today);
            $nextDate = strtotime($currNextSave);
            $nextSave = '';
            if(strtoupper($cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }
            //$this->user->update_user_wallet_balance(($user->wallet->balance + $amount));
            $newBal = intval($account->balance) + intval($amount);
            $totalBal = intval($savings->balance) + intval($amount);
            $perc = $amount * $savings->commission_percent;
            $commission = intval($savings->commission_balance) + ($amount * $savings->commission_percent);
            if ($nextDate > $todaysDate) {
                $account->update(['mid_payment'=>1, 'balance'=>$newBal]);
                $savings->update(['balance'=>$totalBal, 'commission_balance'=>$commission]);
            } elseif ($nextDate == $todaysDate) {
                if($savings->admin_setup){
                    $account->update(['balance'=>$newBal]);
                    $savings->update([
                        'balance'=>$totalBal,
                        'commission_balance'=>$commission,
                        'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave)),
                    ]);
                }else{
                    $account->update(['balance'=>$newBal, 'next_save'=>date('Y-m-d',strtotime($currNextSave.$nextSave))]);
                    $savings->update(['balance'=>$totalBal, 'commission_balance'=>$commission]);
                }                
            }
            
            $sTransactionData = array(
                'savingsId'=>$savings->id,
                'userId'=>$agentId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Deposit',
                'commission_percent'=>$savings->commission_percent,
                'commission_paid'=>$perc,
            );
            $walTransaction = SavingTransaction::on('mysql::write')->create($sTransactionData);
        }

        return response()->json(['message'=>$msg]);
    }

    public function withdrawFromFunds(Request $request){
        try{

            $request->validate([
                'agent_id'=>'required|uuid',
                'account_id'=>'required',
                'amount'=>'required|integer|numeric|gt:0',
            ]);

            $agentId = $request->agent_id;
            $accId = $request->account_id;
            $amount = intval($request->amount);


            $agent = User::on('mysql::write')->findOrFail($agentId);

            $savings = Saving::on('mysql::write')->findOrFail($accId);

            if($savings->userId != $agent->id){
                return response()->json(['message'=>'Agent not admin of this savings group.']);
            }

            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $agent->wallet->id], ['account_name', 'Wallet ID']])->first();

            $walTr = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=> $agent->wallet->id,
                'type'=>'Credit',
                'amount'=>$amount,
                'description'=>'Agent Savings Withdrawal from funds',
                'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$agent->name,
                'transfer'=>false,
                'transaction_type'=>'wallet',
            ]);

            if($savings->balance < $amount || $amount <= 0){
                $walTr->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Insufficient funds in the savings account.']);
            }

            $newBal = $agent->wallet->balance + $amount;
            $savBal = $savings->balance - $amount;

            $agent->wallet()->update(['balance'=>$newBal]);
            $savings->update(['balance'=>$savBal]);

            SavingTransaction::on('mysql::write')->create([
                'savingsId'=>$savings->id,
                'userId'=>$agentId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Withdrawal'
            ]);

            $walTr->update([
                'status'=>'success',
            ]);

            return response()->json(['message'=>'Withdrawal successfull.']);

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function memberWithdrawalRequest(Request $request){
        try{
            $request->validate([
                'user_id'=>'required',
                'amount'=>'required|integer|numeric',
                'account_id'=>'required',
                'description'=>'nullable',
            ]);

            $amount = intval($request->amount);

            $savings = Saving::on('mysql::read')->findOrFail($request->account_id);

            $member = AgentSavings::on('mysql::read')->where([['account_id', $request->account_id], ['user_id', $request->user_id]])->get()->first();
            if(!$member){
                return response()->json(['message'=>'User not a member of this agent savings group.'], 420);
            }

            if($amount > $member->balance){
                return response()->json(['message'=>'Insufficient balance.'], 420);
            }

            $withdraw = WithdrawalRequest::on('mysql::write')->create([
                'user_id'=>$member->user_id,
                'group_id'=>$savings->id,
                'amount'=>$amount,
                'description'=>$request->description,
                'status'=>'pending',
            ]);
            
            return response()->json(['message'=>'Withdrawal Request successful.']);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function approveWithdrawalRequest(Request $request){
        try{
            $request->validate([
                'request_id'=>'required',
                'accept_or_decline'=>'required|boolean',
                'agent_id'=>'required',
            ]);

            $msg = '';

            $withReq = WithdrawalRequest::on('mysql::write')->findOrFail($request->request_id);

            if($withReq->status == 'declined'){
                return response()->json(['message'=>'Withdrawal Request has been declined.'], 420);
            }elseif($withReq->status == 'approved'){
                return response()->json(['message'=>'Withdrawal Request has been approved.'], 420);
            }
            
            if($request->accept_or_decline){
                $amount = intval($withReq->amount);
                $user_id = $withReq->user_id;
                $acc_id = $withReq->group_id;

                $acc = Saving::on('mysql::write')->findOrFail($acc_id);

                $member = AgentSavings::on('mysql::write')->where([['account_id', $acc_id], ['user_id', $user_id]])->get()->first();

                if(!$member){
                    return response()->json(['message'=>'User not member of this Agent group savings.'], 404);
                }
                $user = User::on('mysql::write')->findOrFail($user_id);

                $newAmount = $user->wallet->balance + $amount;
                $debit = $member->balance - $amount;
                $accDebit = $acc->balance - $amount;

                $user->wallet()->update(['balance'=>$newAmount]);
                $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $user->wallet->id], ['account_name', 'Wallet ID']])->first();
                WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=> $user->wallet->id,
                    'type'=>'Credit',
                    'amount'=>$amount,
                    'description'=>'Agent Savings Withdrawal',
                    'receiver_account_number'=>$acc->account_number,
                    'receiver_name'=>$user->name,
                    'transfer'=>false,
                    'transaction_type'=>'wallet',
                    'status'=>'success',
                ]);

                $member->update(['balance'=>$debit]);
                $acc->update(['balance'=>$accDebit]);
                $withReq->update(['status'=>'approved']);

                SavingTransaction::on('mysql::write')->create([
                    'savingsId'=>$acc->id,
                    'userId'=>$user->id,
                    'amount'=>$amount,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Savings Withdrawal'
                ]);

                $msg = 'Withdrawal Successful';

            }else{
                $withReq->update(['status'=>'declined']);
                $msg = 'Withdrawal Request declined.';
            }
            
            return response()->json(['message'=>$msg, 'request'=>$withReq]);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function maturedSavings(Request $request){
        try{

            $request->validate([
                'agent_id'=>'required',
                'account_id'=>'required',
            ]);
            $accs = array();
            $today = strtotime(date('Y-m-d'));
            $acc_id = $request->account_id;
            $agent_id = $request->agent_id;

            $savings = Saving::on('mysql::read')->findOrFail($acc_id);

            $members = AgentSavings::on('mysql::read')->where('account_id', $acc_id)->get();
            
            if($savings->admin_setup){
                $endDate = $savings->end_date;
                if(strtotime($endDate) <= $today){
                    return response()->json(['message'=>'Matured Savings retrieved.', 'accounts'=>$members]);
                }else{
                    return response()->json(['message'=>'No Matured Savings.'], 404);
                }
            }else{
                foreach($members as $member){
                    $endDate = strtotime($member->end_date);
                    if($endDate <= $today){
                        $accs[] = $member;
                    }
                }

                if(empty($accs)){
                    return response()->json(['message'=>'No Matured Savings.'], 404);
                }

                return response()->json(['message'=>'Matured Savings retrieved.', 'accounts'=>$accs]);
            }
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function closeAccount(Request $request){
        try{

            $request->validate([
                'user_id'=>'required',
                'account_id'=>'required',
                'reason'=>'nullable',
            ]);

            $accId= $request->account_id;
            $userId = $request->user_id;
            $reason = $request->reason;

            $acc = Saving::on('mysql::write')->findOrFail($accId);
            if(!$acc){
                return response()->json(['message'=>'Unable to retrieve Account.'], 404);
            }
            $user = User::on('mysql::write')->findOrFail($userId);
            if(!$user){
                return response()->json(['message'=>'Unable to retrieve User.'], 404);
            }

            $member = AgentSavings::on('mysql::write')->where([['account_id', $acc->id], ['user_id', $userId]])->get()->first();

            if(!$member){
                return response()->json(['message'=>'Unable to retrieve Member.'], 404);
            }
            //return 1234;
            if($acc->admin_setup){
                $endDate = $acc->end_date;
            }else{
                $endDate = $member->end_date;
            }

            $savingsBalance = intval($member->balance);
            $currWalletBalance = intval($user->wallet->balance);
            $newWalletBalance = $savingsBalance + $currWalletBalance;
            $newSavingBal = $acc->balance - $savingsBalance;

            if(strtotime(date('Y-m-d')) < strtotime($endDate)){
                $newWalletBalance = $newWalletBalance - ($savingsBalance * $acc->penalty);
                $newSavingBal = $newSavingBal + ($savingsBalance * $acc->penalty);
            }

            //Update wallet balance
            $user->wallet()->update(['balance'=>$newWalletBalance]);

            $acc->update(['balance'=>$newSavingBal]);
            
            $member->update(['balance'=>0]);

            //$deleteSavingsTransaction = SavingTransaction::where([['savingsId', $acc->id], ['userId', $acc->userId]])->delete();

            //$acc->delete();
            $member->delete();
            $break = BreakSaving::on('mysql::write')->create([
                'user_id'=>$userId,
                'account_id'=>$accId,
                'reason'=>$reason,
                'explanation'=>'Agent Account:'
            ]);
            return response()->json(['message'=>'Savings Account successfully closed.'], 200);
        }catch(ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function setPenalty(Request $request){
        try{

            $request->validate([
                'agent_id'=>'required',
                'account_id'=>'required',
                'penalty'=>'required|integer|numeric',
            ]);

            $accId= $request->account_id;
            $agentId = $request->agent_id;
            $penalty = $request->penalty;

            $acc = Saving::on('mysql::write')->findOrFail($accId);
            if(!$acc){
                return response()->json(['message'=>'Unable to retrieve Account.'], 404);
            }

            if($acc->userId != $agentId){
                return response()->json(['message'=>'You are not the agent in charge of this account.']);
            }

            $doublePen = intval($penalty) / 100;

            $acc->update(['penalty'=>$doublePen]);
            return response()->json(['message'=>'Penalty for breaking account set successfully.'], 200);
        }catch(ValidationException $exception){
            return response()->json(['message'=>$exception->getMessage(), 'errors'=>$exception->errors()], 422);
        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function allWithdrawalRequests($groupId){

        //return $groupId;
        try{
            $requests = WithdrawalRequest::on('mysql::read')->where([['group_id', $groupId],['status', 'pending']])->get();

            if(!$requests){
                return response()->json(['message'=>'Unable to retrieve requests.'], 404);
            }

            if(!$requests->count() || $requests == null){
                return response()->json(['message'=>'No withdrawal requests at the moment.', 'requests'=>$requests], 404);
            }

            return response()->json(['requests'=>$requests], 200);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function withdrawCommission(Request $request){
        try{

            $request->validate([
                'agent_id'=>'required|uuid',
                'account_id'=>'required',
                'amount'=>'required|integer|numeric|gt:0',
            ]);

            $agentId = $request->agent_id;
            $accId = $request->account_id;
            $amount = intval($request->amount);


            $agent = User::on('mysql::write')->findOrFail($agentId);

            $savings = Saving::on('mysql::write')->findOrFail($accId);

            if($savings->userId != $agent->id){
                return response()->json(['message'=>'Agent not admin of this savings group.']);
            }

            $acc = AccountNumber::on('mysql::read')->where([['wallet_id', $agent->wallet->id], ['account_name', 'Wallet ID']])->first();
            $walTr = WalletTransaction::on('mysql::write')->create([
                'wallet_id'=> $agent->wallet->id,
                'type'=>'Credit',
                'amount'=>$amount,
                'description'=>'Agent Savings Commission Withdrawal',
                'receiver_account_number'=>$acc->account_number,
                'receiver_name'=>$agent->name,
                'transfer'=>false,
                'transaction_type'=>'wallet',
            ]);

            if($savings->commission_balance < $amount || $amount <= 0){
                $walTr->update([
                    'status'=>'failed',
                ]);
                return response()->json(['message'=>'Insufficient funds in the commission balance.']);
            }

            $newBal = $agent->wallet->balance + $amount;
            $savBal = $savings->commission_balance - $amount;

            $agent->wallet()->update(['balance'=>$newBal]);
            $savings->update(['commission_balance'=>$savBal]);

            SavingTransaction::on('mysql::write')->create([
                'savingsId'=>$savings->id,
                'userId'=>$agentId,
                'amount'=>$amount,
                'date_deposited'=>date('Y-m-d H:i:s'),
                'type'=>'Commission Withdrawal'
            ]);

            $walTr->update([
                'status'=>'success',
            ]);

            return response()->json(['message'=>'Commission Withdrawal successfull.']);

        }catch(ModelNotFoundException $me){
            return response()->json(['message'=>$me->getMessage()], 404);
        }catch(ValidationException $ve){
            return response()->json(['message'=>$ve->getMessage(), 'errors'=>$ve->errors()], 422);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

    public function usersGroups($userId){
        try{
            $gdetails = array();
            $groups = AgentSavings::on('mysql::read')->where('user_id', $userId)->get();

            if(!$groups)
            {
                return response()->json(['message'=>'Unable to retrieve users groups.'], 404);
            }

            foreach($groups as $group){
                $details = Saving::on('mysql::read')->find($group->account_id);
                $temp  = array();
                $temp['main_acccount'] = $details;
                $temp['sub_account'] = $group;
                //$gdetails[] = $details;
                array_push($gdetails, $temp);
            }

            return response()->json(['groups'=>$gdetails]);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()], 422);
        }
    }

}
