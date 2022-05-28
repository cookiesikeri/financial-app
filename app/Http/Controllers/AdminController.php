<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\AccountNumber;
use App\Models\AgentSavings;
use App\Models\AirtimeTransaction;
use App\Models\Business;
use App\Models\BusinessCardRequest;
use App\Models\BusinessKyc;
use App\Models\CardRequestPhysical;
use App\Models\CardRequestVirtual;
use App\Models\CustomerValidation;
use App\Models\DataTransaction;
use App\Models\GroupSaving;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Pos;
use App\Models\PosRequest;
use App\Models\PosTransaction;
use App\Models\PowerTransaction;
use App\Models\RotationalSaving;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\TVTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Traits\ManagesResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Searchable\Search;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    use ManagesResponse;
    protected $jwt;
    protected $utility;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->utility = new Functions();
//        $this->middleware('permission:see users kyc level|see users transaction|can view bill amount|can see loan requests|view loan amount|view loan details|can view Businesses|can view business Details|can view Business Transactions')->except('login', 'logout');
        $this->middleware('permission:can suspend users,admin')->only('changeUserStatus', 'changeUserAuthorization', 'userActivation');
        $this->middleware('permission:can create users,admin')->only('createStaff');
        $this->middleware('permission:can view agent details,admin')->only('index','totalNumbers','fetchStaffs');
        $this->middleware('permission:view transaction amount,admin')->only('searchTransactions');
        $this->middleware('permission:can view all bills,admin')->only('allBillPayments');
        $this->middleware('permission:can view transactions,admin')->only('allSavings');
    }

    //fetch users
    public function index()
    {
        $users = User::paginate(10);
        foreach($users as $key => $user){
            $wallet = Wallet::where('user_id', $user->id)->first();
            if($wallet && $wallet != null){
                $accounts = AccountNumber::where('wallet_id', $wallet->id)->get();
                $user['wallet'] = $wallet;
                $user['account_numbers'] = $accounts;
            }else{
                $user['wallet'] = null;
                $user['account_numbers'] = null;
            }

            $user->makeVisible('phone');
            $uAuth = CustomerValidation::selectRaw('customer_validations.authorized_stat')->where('user_id', $user->id)->get()->toArray();
            $user['authorized_stat'] = $uAuth ? $uAuth[0]['authorized_stat'] : null;
        }

        return $this->sendResponse($users, 'All users');
    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|string',
                'password' => 'nullable|string|min:6',
            ]);

            // return $request;
            if ($validator->fails()) {
                return $this->sendError($validator->errors(),[],422);
            }

            $credentials = $request->only(['email', 'password']);
            if (!$token = Auth::guard('admin')->attempt($credentials)) {
                return $this->sendError('Invalid credentials', [],401);
            }

            $admin = Auth::guard('admin')->user();

            $admin['permissions'] = $admin->getAllPermissions();

            return $this->sendResponse(['admin'=>$admin,'access_token'=>$token, 'expires'=>auth('admin')->factory()->getTTL() * 60 * 2,], 'Logged in');

        }catch(Exception $e){
            return $this->sendError($e->getMessage(),[],422);
        }
    }

    public function logout()
    {
        auth('admin')->logout();
        return $this->sendResponse([],'Successfully logged out');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function searchUsers($key_word){
        $res = array();
        $user = (new Search())
                ->registerModel(User::class, ['name', 'email'])
                ->registerModel(AccountNumber::class, 'account_number')
                ->search($key_word);
        foreach($user->groupByType() as $type => $u)
        {
            foreach($u as $sres)
            {
                if($sres->type == 'account_numbers')
                {
                    $wallet = Wallet::find($sres->searchable->wallet_id);
                    if(!$wallet)
                    {
                        return $this->sendError('No User with this account number.');
                    }
                    $res[] = $wallet->user;
                }
                else
                {
                    $res[] = $sres->searchable;
                }
            }
        }

        return $this->sendResponse($res,'Successful');
    }


    public function userInfo($user_id){
        $user = User::find($user_id);
        $user_transactions = WalletTransaction::where('wallet_id', $user->wallet->id)->get();
        $accs = AccountNumber::where('wallet_id', $user->wallet->id)->get();
        $user_kyc = Kyc::where('user_id', $user_id)->first();
        $user_wallet = $user->wallet;
        $user_airtime = AirtimeTransaction::where('user_id', $user_id)->get();
        $user_data = DataTransaction::where('user_id', $user_id)->get();
        $user_tv = TVTransaction::where('user_id', $user_id)->get();
        $user_power = PowerTransaction::where('user_id', $user_id)->get();
        $user['account_numbers'] = $accs;
        $user->makeVisible('phone');
        $user->makeVisible('bvn');

        return $this->sendResponse([
            'user' => $user,
            'user_transactions' => $user_transactions,
            'user_kyc' => $user_kyc,
            //'user_wallet' => $user_wallet,
            'user_airtime_transactions' => $user_airtime,
            'user_tv_transactions' => $user_tv,
            'user_data_transactions' => $user_data,
            'user_power_transactions' => $user_power,
            ],
            'User info'
        );
    }

    public function totalNumbers(){
        $users = User::all()->count();
        $business = Business::all()->count();
        $agentSavings = Saving::where('type', 'Agent')->count();
        $groupSavings = Saving::where('type', 'Group')->count();
        $personalSavings = Saving::where('type', 'Personal')->orWhere('type', 'Fixed')->orWhere('type', 'Flex')->count();
        $pos = Pos::all()->count();
        $posRequest = PosRequest::all()->count();


        return $this->sendResponse([
            'users'=>$users,
            'business'=>$business,
            'agent_savings'=>$agentSavings,
            'group_savings'=>$groupSavings,
            'personal_savings'=>$personalSavings,
            'pos'=>$pos,
            'pos_requests'=>$posRequest,
            ],
            'Count result.'
        );
    }

    public function changeUserStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'action'=>'required|string',
            'user_ids'=>'required|array',
        ]);

        $res = array();

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $action = $request->action;
        if($action != 'suspend' || $action != 'activate')
        {
            return $this->sendError('Allowed actions are "suspend" or "activate" ');
        }

        foreach($request->user_ids as $user_id){
            $user = User::find($user_id);
            if($action == 'suspend'){
                $user->update([
                    'status'=> 2,
                ]);
                $track = $this->utility->insertIssueTracker($user->id, Auth::guard('admin')->id(), 'Suspended User');
            }elseif($action == 'activate'){
                $user->update([
                    'status'=>1,
                ]);
                $track = $this->utility->insertIssueTracker($user->id, Auth::guard('admin')->id(), 'Activated User');
            }

            $res[] = $user;
        }
        return $this->sendResponse($res, 'User(s) status updated.');
    }

    public function changeUserAuthorization(Request $request){
        $validator = Validator::make($request->all(), [
            'authorization'=>'required|boolean',
            'user_ids'=>'required|array',
        ]);

        $res = array();

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $action = $request->authorization ? true : false;
        foreach($request->user_ids as $user_id){
            //$userAuth = CustomerValidation::where('user_id',$user_id);
            //$userAuth->update(['authorized_stat'=>$action]);
            $user = User::find($user_id);
            if($user->customer_verification){
                $user->customer_verification->update(['authorized_stat'=>$action, 'support_id'=>Auth::guard('admin')->id()]);
                $track = $this->utility->insertIssueTracker($user->id, Auth::guard('admin')->id(), 'Changed Users authorization to '. $action);
                $res[] = $user;
            }
        }

        return $this->sendResponse($res, 'User(s) authorization updated.');
    }

    public function searchTransactions(Request $request){
        $transactions = WalletTransaction::whereNotNull('created_at');

        if($request->has('date')){
            $transactions->where('created_at', '>', $request->date);
        }

        if($request->has('transaction_type')){
            $transactions->where('transaction_type', $request->transaction_type);
        }

        if($request->has('status')){
            $transactions->where('status', $request->status);
        }

        if($request->has('transfer')){
            $transactions->where('transfer', $request->transfer);
        }

        return $this->sendResponse($transactions->get(), 'Search results');
    }

    public function allBillPayments(){
        /* $airtime = AirtimeTransaction::whereNotNull('created_at');
        $data = DataTransaction::whereNotNull('created_at');
        $tv = TVTransaction::whereNotNull('created_at');
        $power = PowerTransaction::whereNotNull('created_at'); */

        $airtime = AirtimeTransaction::latest('date_created')->get();
        $data = DataTransaction::latest('created_at')->get();
        $tv = TVTransaction::latest('date_created')->get();
        $power = PowerTransaction::latest('created_at')->get();

        return $this->sendResponse([
            'airtime_transactions' => $airtime,
            'data_transactions' => $data,
            'tv_transactions' => $tv,
            'power_transactions' => $power,
            ],
            'Bill payment transactions'
        );
    }

    public function allTransactions(){

        $transactions = WalletTransaction::latest('created_at')->get();

        return $this->sendResponse([
            'transactions' => $transactions,
            ],
            'Wallet transactions'
        );
    }

    public function allSavings(){

        $personal = Saving::where('type', 'Personal')->orWhere('type', 'Fixed')->orWhere('type', 'Flex')->get();
        $group = Saving::where('type', 'Group')->get();
        $rotational = Saving::where('type', 'Rotational')->get();
        $agent = Saving::where('type', 'Agent')->get();

        return $this->sendResponse([
            'personal_savings' => $personal,
            'group_savings' => $group,
            'rotational_savings' => $rotational,
            'agent_savings' => $agent,
            ],
            'All savings'
        );
    }

    public function savingsInfo($savings_id){
        $savings = Saving::find($savings_id);
        $savings_transactions = SavingTransaction::where('savingsId', $savings_id)->get();
        $type = $savings->type;
        $members = array();
        switch($type){
            case 'Personal':
                break;
            case 'Fixed':
                break;
            case 'Flex':
                break;
            case 'Group':
                $members = GroupSaving::where('account_id', $savings_id)->get();
                break;
            case 'Rotational':
                $members = RotationalSaving::where('account_id', $savings_id)->get();
                break;
            case 'Agent':
                $members = AgentSavings::where('account_id', $savings_id)->get();
                break;
            default:

        }

        return $this->sendResponse([
            'savings' => $savings,
            'savings_transactions' => $savings_transactions,
            'savings_members' => $members,
            ],
            'Savings info'
        );
    }

    public function allLoans(){

        $loans = Loan::all();

        return $this->sendResponse([
            'loan_transactions' => $loans,
            ],
            'Loan transactions'
        );
    }

    public function allBusiness(){
        $business = Business::all();
        $business_transactions = array();

        foreach($business as $biz){
            $transactions = WalletTransaction::where('wallet_id', $biz->wallet->id)->get();
            array_push($business_transactions, $transactions);
        }

        return $this->sendResponse([
            'business' => $business,
            'business_transactions' => $business_transactions,
            ],
            'All Business'
        );
    }

    public function businessInfo($business_id){
        $business = Business::find($business_id);
        $business_transactions = WalletTransaction::where('wallet_id', $business->wallet->id)->get();
        $business_kyc = BusinessKyc::where('business_id', $business_id)->first();
        $business_staff = $business->staff;

        return $this->sendResponse([
            'business' => $business,
            'business_transactions' => $business_transactions,
            'business_kyc' => $business_kyc,
            //'business_staff' => $business_staff,
            //'business_wallet' => $business->wallet,
            ],
            'Business info'
        );
    }

    public function allCardRequests(){

        $business_card_requests = BusinessCardRequest::latest()->get();
        $physical_card_requests = CardRequestPhysical::latest()->get();
        $virtual_card_requests = CardRequestVirtual::latest()->get();
        $all = array();
        //$all = array_merge($business_card_requests, $physical_card_requests, $virtual_card_requests);
        array_push($all, $business_card_requests);
        array_push($all, $physical_card_requests);
        array_push($all, $virtual_card_requests);

        return $this->sendResponse([
            'all_card_request' => $all,
            'business_card_request' => $business_card_requests,
            'users_physical_card_request' => $physical_card_requests,
            'users_virtual_card_request' => $virtual_card_requests,
            ],
            'Card Requests'
        );
    }

    public function allPOS(){

        $pos = Pos::latest()->get();
        $pos_requests = PosRequest::latest()->get();
        $pos_transactions = PosTransaction::latest()->get();

        foreach($pos_requests as $pr)
        {
            $pr->pos_locations;
        }
        return $this->sendResponse([
            'pos' => $pos,
            'pos_requests' => $pos_requests,
            'pos_transactions' => $pos_transactions,
            ],
            'All POS'
        );
    }

    public function fetchStaffs()
    {
        try{
            $users = Admin::where('role', 'support')->get();

            return $this->sendResponse($users, 'All staffs successfully fetched');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->getMessage());
        }
    }

    public function createStaff(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'lname' => 'required',
                'fname' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return response()->json(['message' => $validator->errors()],422);
            }

            $input = $request->all();

            $input['password'] = Hash::make($request->input('password'));
            $input['role'] = 'support';

            Admin::updateOrCreate([
                'email' => $request->input('email')
            ],$input);

            return $this->sendResponse([], 'Staffs successfully created');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->getMessage());
        }
    }

    public function exportTransactions(Request $request){
        $fileName = 'trans.csv';
        $transactions = WalletTransaction::all();

        $headers = array(
            "Content-type"=>"text/csv",
            "Content-Disposition"=>"attachment; filename=$fileName",
            "Pragma"=>"no-cache",
            "Cache-Control"=>"must-revalidate, post-check=0, pre-check=0",
            "Expires"=>"0",
        );

        $columns = array('Title', 'Assign', 'Description', 'Start Date', 'Due Date');

        $callback = function() use($transactions, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $task) {
                $row['Title']  = $task->wallet_id;
                $row['Assign']    = $task->amount;
                $row['Description']    = $task->description;
                $row['Start Date']  = $task->created_at;
                $row['Due Date']  = $task->updated_at;

                fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
                //fputcsv($file, array($task));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function userActivation(Request $request,$change)
    {
        $validator = Validator::make($request->all(),[
           'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],422);
        }

        $userId = $request->input('user_id');
        if($change === 'user-state'){
            $user = User::findOrFail($userId);
            $user->update([
                'status' => $user->status === 1 ? 2 : 1
            ]);
            $track = $this->utility->insertIssueTracker($user->id, Auth::guard('admin')->id(), 'User state updated');
            return $this->sendResponse([], 'User state has been updated');
        } elseif ($change === 'user-authorizer'){
            $authorizer = CustomerValidation::where('user_id',$userId)->first();
            $authorizer->update([
                'authorized_stat' => $authorizer->authorized_stat === 1 ? 0 : 1
            ]);
            $track = $this->utility->insertIssueTracker($userId, Auth::guard('admin')->id(), 'User has been activated for transaction');
            return $this->sendResponse([], 'User has been activated for transaction');
        } else {
            return $this->sendError('Route does not exist ');
        }
    }
}
