<?php

namespace App\Http\Controllers\Apis\loans;

use App\Http\Controllers\Controller;
use App\Models\PaymentCard;
use App\Traits\ManagesBanks;
use App\Traits\ManagesResponse;
use App\Traits\ManagesRestful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @group Payment Cards
 *
 * APIs for handling payment cards
 */
class PaymentCardController extends Controller
{
    use ManagesBanks, ManagesResponse, ManagesRestful;
    /**
     * Display a listing of payment cards.
     *
     * @queryParam user_id string. The user ID from the users table.
     * @queryParam loan_account_id string. The loan account ID from the loan_accounts table.
     * @queryParam bank_id string. The bank ID from list of available banks.
     * @queryParam account_number string. The account number of the payment card.
     * @queryParam account_name string. The account name on the payment card.
     * @queryParam card_number integer. The card number on the debit card.
     *
     * @response {
     * "success": true,
     * "data": {
     * "current_page": 1,
     * "data": [
     * {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "bank_id": 078,
     * "account_number": 3454567899,
     * "account_name": Joseph Gyam,
     * "card_number": 54564554567894,
     * "expiry_date": 2021-06-11 06:45,
     * "cvv": 007,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * },
     * {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "bank_id": 078,
     * "account_number": 3454567899,
     * "account_name": Joseph Gyam,
     * "card_number": 54564554567894,
     * "expiry_date": 2021-06-11 06:45,
     * "cvv": 007,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
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
        $cards = $this->getAllResources('payment-cards');
        return $this->sendResponse($cards, 'payment cards retrieved successfully');
    }

    /**
     * Check for payment card validity.
     *
     * @urlParam bin integer required. The first 6 digits on a debit card.
     *
     * @response {
     * "success": true,
     * "data": {
     * "bin": "519345",
     * "brand": "mastercard",
     * "sub_brand": "",
     * "country_code": "NG",
     * "country_name": "Nigeria",
     * "card_type": "DEBIT",
     * "bank": "Stanbic IBTC Bank",
     * "linked_bank_id": 23,
     * },
     * "message": "card details retrieved successfully",
     * "status": "success"
     * }
     * @param $bin
     * @return \Illuminate\Http\Response
     */
    public function check($bin)
    {
        $card = $this->validateBankCard($bin);
        if ($card && $card['card_type'] == 'DEBIT') {
            return $this->sendResponse($card, 'card details retrieved successfully');
        }
        return $this->sendError('invalid debit card');
    }

    /**
     * Display listing of available banks
     *
     * @response {
     * "success": true,
     * "data": [
     * {
     * "name": "Abbey Mortgage Bank",
     * "slug": "abbey-mortgage-bank",
     * "code": "801",
     * "longcode": "",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": false,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 174,
     * "createdAt": "2020-12-07T16:19:09.000Z",
     * "updatedAt": "2020-12-07T16:19:19.000Z"
     * },
     * {
     * "name": "Access Bank",
     * "slug": "access-bank",
     * "code": "044",
     * "longcode": "044150149",
     * "gateway": "emandate",
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 1,
     * "createdAt": "2016-07-14T10:04:29.000Z",
     * "updatedAt": "2020-02-18T08:06:44.000Z"
     * },
     * {
     * "name": "Access Bank (Diamond)",
     * "slug": "access-bank-diamond",
     * "code": "063",
     * "longcode": "063150162",
     * "gateway": "emandate",
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 3,
     * "createdAt": "2016-07-14T10:04:29.000Z",
     * "updatedAt": "2020-02-18T08:06:48.000Z"
     * },
     * {
     * "name": "ALAT by WEMA",
     * "slug": "alat-by-wema",
     * "code": "035A",
     * "longcode": "035150103",
     * "gateway": "emandate",
     * "pay_with_bank": true,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 27,
     * "createdAt": "2017-11-15T12:21:31.000Z",
     * "updatedAt": "2021-02-18T14:55:34.000Z"
     * },
     * {
     * "name": "ASO Savings and Loans",
     * "slug": "asosavings",
     * "code": "401",
     * "longcode": "",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",

     * "currency": "NGN",
     * "type": "nuban",
     * "id": 63,
     * "createdAt": "2018-09-23T05:52:38.000Z",
     * "updatedAt": "2019-01-30T09:38:57.000Z"
     * },
     * {
     * "name": "Bowen Microfinance Bank",
     * "slug": "bowen-microfinance-bank",
     * "code": "50931",
     * "longcode": "",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": false,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 108,
     * "createdAt": "2020-02-11T15:38:57.000Z",
     * "updatedAt": "2020-02-11T15:38:57.000Z"
     * },
     * {
     * "name": "CEMCS Microfinance Bank",
     * "slug": "cemcs-microfinance-bank",
     * "code": "50823",
     * "longcode": "",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": false,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 74,
     * "createdAt": "2020-03-23T15:06:13.000Z",
     * "updatedAt": "2020-03-23T15:06:28.000Z"
     * },
     * {
     * "name": "Citibank Nigeria",
     * "slug": "citibank-nigeria",
     * "code": "023",
     * "longcode": "023150005",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 2,
     * "createdAt": "2016-07-14T10:04:29.000Z",
     * "updatedAt": "2020-02-18T20:24:02.000Z"
     * },
     * ],
     * "message": "list of available banks retrieved successfully",
     * "status": "success"
     * }
     * @return \Illuminate\Http\Response
     */
    public function banks()
    {
        $banks = $this->getAvailableBankList();
        if ($banks) {
            return $this->sendResponse($banks, 'list of available banks retrieved successfully');
        }
        return $this->sendResponse([], 'no bank available at the moment');
    }

    /**
     * Display a specified bank detail
     *
     * @urlParam id integer required. The ID of the bank.
     * @response {
     * "success": true,
     * "data": {
     * "name": "Stanbic IBTC Bank",
     * "slug": "stanbic-ibtc-bank",
     * "code": "221",
     * "longcode": "221159522",
     * "gateway": null,
     * "pay_with_bank": false,
     * "active": true,
     * "is_deleted": null,
     * "country": "Nigeria",
     * "currency": "NGN",
     * "type": "nuban",
     * "id": 14,
     * "createdAt": "2016-07-14T10:04:29.000Z",
     * "updatedAt": "2020-02-18T20:24:17.000Z"
     * },
     * "message": "bank retrieved successfully",
     * "status": "success"
     * }
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function bank($id)
    {
        $bank = $this->getBankById($id);
        return $this->sendResponse($bank, 'bank retrieved successfully');
    }

    /**
     * Store a newly created payment card details in storage.
     *
     * @bodyParam bank_id integer required. The bank ID of the selected bank.
     * @bodyParam loan_account_id string required. The loan account id of the borrower.
     * @bodyParam account_number string required. The loanee bank account number.
     * @bodyParam account_name string required. The name of the bank account.
     * @bodyParam card_number string required. The card number.
     * @bodyParam expiry_date string required. The card expiry date. e.g 2022-05-31 09:45:16
     * @bodyParam cvv string required. The CVV on the card.
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "bank_id": 078,
     * "account_number": 3454567899,
     * "account_name": "Joseph Gyam",
     * "card_number": 54564554567894,
     * "expiry_date": 2021-06-11 06:45,
     * "cvv": 007,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * },
     * "message": "card details saved successfully",
     * "status": "success",
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id' => 'required',
            'loan_account_id' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvv' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation fail', $validator->errors());
        }
        //validate card
        $isCardValid = $this->isCardDetailsMatched($request->get('card_number'), $request->get('bank_id'));
        if ($isCardValid) {
            $inputs = $request->only(['bank_id', 'loan_account_id', 'account_number', 'account_name', 'card_number', 'expiry_date', 'cvv']);
            $card = PaymentCard::on('mysql::write')->create($inputs);

            if ($card) {
                return $this->sendResponse($card, 'card details saved successfully');
            }
            return $this->sendError('unable to save card details');
        }
        return $this->sendResponse(null, 'could not validate payment card');
    }

    /**
     * Display the specified payment card details.
     *
     * @urlParam id integer required The ID of the payment card.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "bank_id": 078,
     * "account_number": 3454567899,
     * "account_name": Joseph Gyam,
     * "card_number": 54564554567894,
     * "expiry_date": 2021-06-11 06:45,
     * "cvv": 007,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * },
     * "message": "card details retrieved successfully",
     * "status": "success",
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = PaymentCard::on('mysql::read')->whereId($id)->with(['user', 'loanaccount'])->first();
        if ($card) {
            return $this->sendResponse($card, 'card details retrieved successfully');
        }
        return $this->sendResponse(null, 'unable to get card details');
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
     * Update the specified payment card info in storage.
     *
     * @urlParam id integer required The ID of the payment card.
     * @body
     * @response {
     * "success": true,
     * "data": {
     * "id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "loan_account_id": "ffade430-2f2e-470e-b6a6-07f01a5f6fbd",
     * "bank_id": 078,
     * "account_number": 3454567899,
     * "account_name": Joseph Gyam,
     * "card_number": 54564554567894,
     * "expiry_date": 2021-06-11 06:45,
     * "cvv": 007,
     * "created_at": "2021-05-25T19:43:51.000000Z",
     * "updated_at": "2021-05-25T19:43:51.000000Z",
     * },
     * "message": "card details retrieved successfully",
     * "status": "success",
     * }
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified payment card from storage.
     * @urlParam id integer required The ID of the payment card.
     *
     * @response {
     * "success": true,
     * "data": {
     *
     * },
     * "message": "payment card deleted successfully",
     * "status": "success",
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = $this->deleteResource('payment-cards', $id);
        if ($card) {
            return $this->sendResponse(null, 'card details deleted successfully');
        }
        return $this->sendError('unable to delete card details');
    }
}
