<?php

namespace App\Http\Controllers\Apis\loans;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Traits\ManagesBVN;
use App\Traits\ManagesLoans;
use App\Traits\ManagesResponse;
use App\Traits\ManagesRestful;
use App\Traits\ManagesUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * @group Loans
 *
 * APIs for handling loan transactions
 */
class LoanController extends Controller
{

    use ManagesResponse, ManagesBVN, ManagesLoans, ManagesRestful, ManagesUsers;
    /**
     * Display a listing of all loans
     *
     * @queryParam amount integer. The amount of money granted.
     * @queryParam loan_account_id string. The loan account Id of the loanee.
     * @queryParam balance integer. The loan balance remaining to be paid.
     * @queryParam originating_fee integer. The loan originating fee.
     * @queryParam interest integer. The loan interest fee.
     * @response {
     * "success": true,
     * "data": {
     * "current_page": 1,
     * "data": [
     * {
     * "id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "amount": 40000,
     * "balance": 140000,
     * "originating_fee": 60000,
     * "interest": 40000,
     * "is_approved": 1,
     * "expiry_date": "2021-06-04 20:14:44",
     * "created_at": "2021-05-25T20:14:44.000000Z",
     * "updated_at": "2021-05-25T20:14:44.000000Z"
     * },
     * {
     * "id": "1fe54ef0-c4eb-4ad6-b26a-2ccca32f71ca",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "amount": 20000,
     * "balance": 520000,
     * "originating_fee": 300000,
     * "interest": 200000,
     * "is_approved": 1,
     * "expiry_date": "2021-06-04 19:50:24",
     * "created_at": "2021-05-25T19:50:24.000000Z",
     * "updated_at": "2021-05-25T19:50:24.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/loans?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/loans?page=1",
     * "links": [
     * {
     * "url": null,
     * "label": "&laquo; Previous",
     * "active": false
     * },
     * {
     * "url": "http://127.0.0.1:8000/api/loans?page=1",
     * "label": "1",
     * "active": true
     * },
     * {
     * "url": null,
     * "label": "Next &raquo;",
     * "active": false
     * }
     * ],
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/loans",
     * "per_page": 20,
     * "prev_page_url": null,
     * "to": 2,
     * "total": 2
     * },
     * "message": "loans retrieved successfully",
     * "status": "success"
     * }
     * @return \Illuminate\Http\Response
     */
    public function index2()
    {
        if(count(request()->query()) > 0) {
            if (!array_key_exists('page', request()->query())) {
                $results = Loan::on('mysql::read')->where(request()->query())->orderBy('created_at', 'desc')->paginate(20);
            }else {
                $query_without_page = Arr::except(request()->query(), ['page']);
                $results = Loan::on('mysql::read')->where($query_without_page)->orderBy('created_at', 'desc')->paginate(20);
            }

        }else {
            $results = Loan::on('mysql::read')->orderBy('created_at', 'desc')->paginate(20);
        }
        if(!empty($results)) {
            return $this->sendResponse($results->toArray(), 'loans retrieved successfully');
        }
        return $this->sendError('Error in retrieving loan data');
    }

    public function index($user_id)
    {
        $results = Loan::on('mysql::read')->where('user_id', $user_id)->get();
        if(!empty($results)) {
            return $this->sendResponse($results->toArray(), 'loans retrieved successfully');
        }
        return $this->sendError('Error in retrieving loan data');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Create a new loan.
     *
     * @bodyParam amount integer required. The amount of money requested.
     * @bodyParam loan_account_id string required. The loan account id of the borrower.
     * @response {
     * success: true,
     * data: {
     * "id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "amount": 40000,
     * "balance": 50000,
     * "originating_fee": 6000,
     * "interest": 4000,
     * "is_approved": 1,
     * "expiry_date": "2021-06-04 20:14:44",
     * "created_at": "2021-05-25T20:14:44.000000Z",
     * "updated_at": "2021-05-25T20:14:44.000000Z"
     * },
     * message: "new loan created successfully",
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_account_id' => 'required|string',
            'request_amount' => 'required | numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('validation fail', $validator->errors());
        }

        $input = $this->getLoanData($request->get('request_amount'), $request->get('loan_account_id'));
        $input['loan_account_id'] = $request->get('loan_account_id');

        $loan = Loan::on('mysql::write')->create($input);
        if($loan) {
            //add amount to customers wallet
            $existing_wallet_balance = $loan->loanaccount->user->wallet->balance;
            $new_wallet_balance = floatval($existing_wallet_balance) + floatval($loan->amount);
            $updated = $loan->loanaccount->user->wallet->update(['balance' => $new_wallet_balance]);
            if ($updated) {
                return $this->sendResponse($loan, 'new loan created successfully');
            }

        }
        return $this->sendError('error in creating loan');
    }

    /**
     * Display the specified loan.
     * @urlParam id integer required The ID of the loan.
     * @response {
     * success: true,
     * data: {
     * "id": "20bb32bb-04ae-4458-9661-b0a2329d4bc9",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "amount": 40000,
     * "balance": 50000,
     * "originating_fee": 6000,
     * "interest": 4000,
     * "is_approved": 1,
     * "expiry_date": "2021-06-04 20:14:44",
     * "created_at": "2021-05-25T20:14:44.000000Z",
     * "updated_at": "2021-05-25T20:14:44.000000Z"
     * },
     * message: "loan details retrieved successfully",
     * status: "success",
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::on('mysql::read')->find($id);
        if ($loan) {
            $loan['loan_account'] = $loan->loanaccount;
            $loan['user'] = $loan->loanaccount->user;
            return $this->sendResponse($loan, 'loan details retrieved successfully');
        }
        return $this->sendError('error in retrieving loan details');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * calculate the loan amount that can be issued to a borrower
     *
     * @bodyParam amount integer required. The amount of money requested.
     * @bodyParam loan_account_id string required. The loan account id of the borrower.
     * @response {
     * success: true,
     * data: {
     * "amount": 40000,
     * "balance": 50000,
     * "originating_fee": 6000,
     * "interest": 4000,
     * "duration": 10,
     * "expiry_date": "2021-06-04 20:14:44",
     * },
     * message: "allowed loan details returned successfully",
     * status: "success",
     * }
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required | numeric',
            'loan_account_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation fail', $validator->errors());
        }
        $response = $this->getLoanData($request->get('amount'), $request->get('loan_account_id'));
        if ($response) {
            return $this->sendResponse($response, 'allowed loan details returned successfully');
        }
        return $this->sendResponse(null, 'unable to get loan details');
    }

    /**
     * repay a specified loan with wallet to wallet transaction
     *
     * @bodyParam amount integer required. The amount of money repayed.
     * @bodyParam account_number string required. The repayment account number.
     * @urlParam id integer required The ID of the loan.
     * @response {
     * success: true,
     * data: {
     * "wallet_id": 45346997765,
     * "amount": 50000,
     * "type": "Credit",
     * "sender_account_number": 656788888999,
     * "sender_name": "Joe Brown",
     * "receiver_name": "James Palmer",
     * "receiver_account_number": 567234900665,
     * "description": "loan repayment",
     * "transfer": true"
     * },
     * message: "loan repayment successful",
     * status: "success",
     * }
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function repay(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required | numeric',
            'account_number' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse('validation error', $validator->errors());
        }

        //check if loan balance still exist
        if($this->hasPaidLoanBalance($id)) {
            return $this->sendResponse(null,'repayment already completed', 'payed');
        }
        //check if loan balance is sufficient
        $isBalanceSufficient = $this->isWalletBalanceGreaterThanAmount($id, $request->get('amount'));
        if (!$isBalanceSufficient) {
            return $this->sendError('insufficient wallet balance');
        }

        //validate PIN
        $user = Loan::on('mysql::read')->find($id)->loanaccount->user;
        $isPinValid = $this->isPinValid($request->get('pin'), $user);
        if (!$isPinValid) {
            return $this->sendError('invalid PIN');
        }

        $repayData = $request->get('amount'); $repayData['loan_id'] = $id;

        $repayment = $this->saveResource($repayData, 'loan-repayments');
        if ($repayment) {
            $loan = Loan::find($id);
            $loan->balance = floatval($loan->balance) - floatval($repayment->amount);
            $loan->saved();
            $inputs = $request->only(['amount', 'account_number']);
            $inputs['loan_id'] = $id;
            $inputs['description'] = 'repayment for loan with id= '.$loan->id;
            $transaction = $this->walletToWalletTransfer($inputs);
            return $this->sendResponse($transaction, 'loan repayment successful');
        }
        return $this->sendError('unable to create loan repayment');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //
    }

    /**
     * Remove the specified loan from storage.
     * @urlParam id integer required The ID of the loan.
     * @response {
     * success: true,
     * data: {
     *
     * },
     * message: "loan details deleted successfully",
     * status: "success",
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exist = Loan::on('mysql::read')->whereId($id)->exists();
        if($exist) {
            Loan::destroy($id);
            return $this->sendResponse(null, 'loan details deleted successfully');
        }
        return $this->sendError('error in deleting loan details');
    }

    public function bvn(Request $request)
    {
        $data['bvn'] = $request->get('bvn');
        $data['dob'] = $request->get('dob');
        return $this->verifyBVN($data);
        //return $this->sendResponse($res, 'bvn obtained');
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        // return $request;
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $credentials = $request->only(['phone', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->sendResponse($token, 'token created successfully');
    }

    public function activity()
    {
        return $this->saveUserActivity('login', 'auth', auth()->id());
    }

}
