<?php

namespace App\Http\Controllers\Apis\loans;

use App\Http\Controllers\Controller;
use App\Models\LoanKyc;
use App\Models\User;
use App\Traits\ManagesLoans;
use App\Traits\ManagesResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Loan Account
 *
 * APIs for handling loan accounts
 */
class LoanAccountController extends Controller
{
    use ManagesResponse, ManagesLoans;
    /**
     * Display a listing of loan accounts.
     *
     * @queryParam user_id integer. The user ID from users table.
     * @queryParam first_name string. The first name of the loanee.
     * @queryParam last_name string. The last name of the loanee.
     * @queryParam email string. The email of the loanee from users table.
     * @response {
     * "success": true,
     * "data": {
     * "current_page": 1,
     * "data": [
     * {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": "Lubem",
     * "middle_name": null,
     * "last_name": "Tser",
     * "educational_qualification_id": null,
     * "marital_status": 1,
     * "number_of_children": null,
     * "next_of_kin": null,
     * "next_of_kin_phone": null,
     * "emergency_contact_name": null,
     * "emergency_contact_number": null,
     * "other_information": null,
     * "state_id": 7,
     * "lga_id": 116,
     * "city": null,
     * "address": "Gboko South",
     * "residential_status_id": null,
     * "employment_status_id": null,
     * "company": null,
     * "job_title": null,
     * "employment_date": null,
     * "monthly_income_id": 4,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * "email": "enginlubem@ymail.com",
     * "bvn": null,
     * "phone": "08034567890",
     * "sex": null
     * },
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/loan-accounts?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/loan-accounts?page=1",
     * "links": [
     * {
     * "url": null,
     * "label": "&laquo; Previous",
     * "active": false
     * },
     * {
     * "url": "http://127.0.0.1:8000/api/loan-accounts?page=1",
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
     * "path": "http://127.0.0.1:8000/api/loan-accounts",
     * "per_page": 20,
     * "prev_page_url": null,
     * "to": 2,
     * "total": 2
     * },
     * "message": "loan accounts retrieved successfully",
     * "status": "success"
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = LoanKyc::select('loan_accounts.*', 'users.email', 'users.bvn', 'users.phone', 'users.sex')
            ->join('users', 'loan_accounts.user_id', '=', 'users.id')
            ->orderBy('loan_accounts.created_at', 'desc')->paginate(20);
        if($accounts) {
            return $this->sendResponse($accounts, 'loan accounts retrieved successfully');
        }

        return $this->sendResponse([],'could not retrieve loan accounts at the moment');
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
     * Create a new loan account.
     *
     * @bodyParam user_id integer required. The ID of the user from users table.
     * @bodyParam first_name string required. The first name of the loan account user.
     * @bodyParam middle_name string. The middle name of the loan account user.
     * @bodyParam last_name string required. The last name of the loan account user.
     * @bodyParam educational_qualification_id integer. The education qualification ID from educational_qualifications_table
     * @bodyParam marital_status integer. Marital status of the applicant. Default to 1.
     * @bodyParam number_of_children integer. The number of children or wards of the applicant.
     * @bodyParam next_of_kin string required. The name of the next of kin.
     * @bodyParam next_of_kin_phone string. The phone number of the next of kin.
     * @bodyParam emergency_contact_name string. The emergency contact name of the loan account holder.
     * @bodyParam emergency_contact_number string. The emergency contact number of the loan account holder.
     * @bodyParam other_information string. Other information about the loan account holder.
     * @bodyParam state_id integer required. The state ID of the loan account holder from states table.
     * @bodyParam lga_id integer required. The local govt area ID of the loan account holder from lgas table.
     * @bodyParam other_information string. The name of the city of the loan account holder.
     * @bodyParam address string required. The address of the loan account holder
     * @bodyParam residential_status_id string. The residential status ID from residential_statuses table.
     * @bodyParam employment_status_id string. The employment status ID from employment_statuses table.
     * @bodyParam company string. The name of the applicant's company or establishment.
     * @bodyParam job_title string required. The job title or job name of the applicant.
     * @bodyParam employment_date string. The date of employment.
     * @bodyParam monthly_income_id integer required. The ID of the income range from monthly_incomes table.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": "Lubem",
     * "middle_name": null,
     * "last_name": "Tser",
     * "educational_qualification_id": null,
     * "marital_status": 1,
     * "number_of_children": null,
     * "next_of_kin": null,
     * "next_of_kin_phone": null,
     * "emergency_contact_name": null,
     * "emergency_contact_number": null,
     * "other_information": null,
     * "state_id": 7,
     * "lga_id": 116,
     * "city": null,
     * "address": "Gboko South",
     * "residential_status_id": null,
     * "employment_status_id": null,
     * "company": null,
     * "job_title": null,
     * "employment_date": null,
     * "monthly_income_id": 4,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * "email": "enginlubem@ymail.com",
     * "bvn": null,
     * "phone": "08034567890",
     * "sex": null
     * },
     * "message": "loan account created successfully",
     * "status": "success",
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required|string|max:40',
            'last_name' => 'required|string|max:40',
            'state_id' => 'required',
            'address' => 'required',
            'lga_id' => 'required',
            'monthly_income_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse('validation error', $validator->errors());
        }

        $data = $request->except(['email', 'phone', 'bvn', 'gender']);
        $details = $this->createLoanAccount($data);

        if ($details) {
            return $this->sendResponse($details, 'loan account created successfully');
        }
    }

    /**
     * Display the specified loan account.
     * @urlParam id integer required The ID of the loan account.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": "Lubem",
     * "middle_name": null,
     * "last_name": "Tser",
     * "educational_qualification_id": null,
     * "marital_status": 1,
     * "number_of_children": null,
     * "next_of_kin": null,
     * "next_of_kin_phone": null,
     * "emergency_contact_name": null,
     * "emergency_contact_number": null,
     * "other_information": null,
     * "state_id": 7,
     * "lga_id": 116,
     * "city": null,
     * "address": "Gboko South",
     * "residential_status_id": null,
     * "employment_status_id": null,
     * "company": null,
     * "job_title": null,
     * "employment_date": null,
     * "monthly_income_id": 4,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * "email": "enginlubem@ymail.com",
     * "bvn": null,
     * "phone": "08034567890",
     * "sex": null
     * },
     * "message": "loan account retrieved successfully",
     * "status": "success",
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = $this->getLoanAccountDetails($id);

        if($account) {
            return $this->sendResponse($account, 'loan account retrieved successfully');
        }
        return $this->sendResponse(null,'could not retrieve loan account at the moment');
    }

    /**
     * Get all loans that are pending
     *
     * @urlParam id required int. The loan account ID of the loan account holder.
     *
     * @response {
     * "success": true,
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
     * },
     * "status": "success",
     * "message": "pending loan payments retrieved successfully"
     * }
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function pending($id)
    {
        $repayments = $this->getPendingBorrowersLoans($id);
        return $this->sendResponse($repayments, 'pending loan payments retrieved successfully');
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
     * Update the specified loan account in storage.
     *
     * @urlParam id integer required. The ID of the loan account.
     * @bodyParam user_id integer required. The ID of the user from users table.
     * @bodyParam first_name string required. The first name of the loan account user.
     * @bodyParam middle_name string. The middle name of the loan account user.
     * @bodyParam last_name string required. The last name of the loan account user.
     * @bodyParam educational_qualification_id integer. The education qualification ID from educational_qualifications_table
     * @bodyParam marital_status integer. Marital status of the applicant. Default to 1.
     * @bodyParam number_of_children integer. The number of children or wards of the applicant.
     * @bodyParam next_of_kin string required. The name of the next of kin.
     * @bodyParam next_of_kin_phone string. The phone number of the next of kin.
     * @bodyParam emergency_contact_name string. The emergency contact name of the loan account holder.
     * @bodyParam emergency_contact_number string. The emergency contact number of the loan account holder.
     * @bodyParam other_information string. Other information about the loan account holder.
     * @bodyParam state_id integer required. The state ID of the loan account holder from states table.
     * @bodyParam lga_id integer required. The local govt area ID of the loan account holder from lgas table.
     * @bodyParam other_information string. The name of the city of the loan account holder.
     * @bodyParam address string required. The address of the loan account holder
     * @bodyParam residential_status_id string. The residential status ID from residential_statuses table.
     * @bodyParam employment_status_id string. The employment status ID from employment_statuses table.
     * @bodyParam company string. The name of the applicant's company or establishment.
     * @bodyParam job_title string required. The job title or job name of the applicant.
     * @bodyParam employment_date string. The date of employment.
     * @bodyParam monthly_income_id integer required. The ID of the income range from monthly_incomes table.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": "Lubem",
     * "middle_name": null,
     * "last_name": "Tser",
     * "educational_qualification_id": null,
     * "marital_status": 1,
     * "number_of_children": null,
     * "next_of_kin": null,
     * "next_of_kin_phone": null,
     * "emergency_contact_name": null,
     * "emergency_contact_number": null,
     * "other_information": null,
     * "state_id": 7,
     * "lga_id": 116,
     * "city": null,
     * "address": "Gboko South",
     * "residential_status_id": null,
     * "employment_status_id": null,
     * "company": null,
     * "job_title": null,
     * "employment_date": null,
     * "monthly_income_id": 4,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * "email": "enginlubem@ymail.com",
     * "bvn": null,
     * "phone": "08034567890",
     * "sex": null
     * },
     * "message": "loan account updated successfully",
     * "status": "success",
     * }
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:40',
            'last_name' => 'required|string|max:40',
            'state_id' => 'required',
            'lga_id' => 'required',
            'address' => 'required',
            'monthly_income_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse('validation error', $validator->errors());
        }

        $loan_account = $request->except(['email', 'phone', 'bvn', 'gender']);
        $user_account = $request->only(['email', 'phone', 'bvn', 'gender']);

        $account = $this->updateLoanAccount($loan_account, $id);
        if ($account) {
            $user = User::on('mysql::read')->find($account->user_id);
            if ($user->fill($user_account)->save()) {
                return $this->sendResponse($account, 'loan account updated successfully');
            }
        }
        return $this->sendError('loan account was not updated');
    }

    /**
     * Remove the specified loan account from storage.
     * @urlParam id integer required The ID of the loan account.
     * @response {
     * "success": true,
     * "data": {
     *
     * },
     * "message": "loan account deleted successfully",
     * "status": "success",
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(LoanKyc::on('mysql::read')->where('id', $id)->exists()) {
            LoanKyc::on('mysql::write')->destroy($id);
            return $this->sendResponse([], 'loan account deleted successfully');
        }
        return $this->sendError('unable to delete loan account');
    }
}
