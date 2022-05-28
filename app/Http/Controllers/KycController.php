<?php

namespace App\Http\Controllers;

use App\Enums\AccountRequestType;
use App\Enums\AccountTypes;
use App\Mail\KycEditMail;
use App\Models\AccountRequest;
use App\Models\Kyc;
use App\Models\User;
use App\Traits\ManagesResponse;
use App\Traits\ManagesUploads;
use App\Traits\ManagesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @group KYC
 *
 * APIs for handling a central KYC
 */
class KycController extends Controller
{
    use ManagesResponse, ManagesUploads, ManagesUsers;

    /**
     * Display a listing of all kyc
     *
     * @queryParam user_id string The user id of the kyc.
     *
     * @response {
     * "success": true,
     * "data": [
     * {
     * "id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": null,
     * "last_name": null,
     * "middle_name": null,
     * "address": null,
     * "home_address": null,
     * "proof_of_address_url": null,
     * "id_card_number": null,
     * "id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
     * "next_of_kin": null,
     * "next_of_kin_contact": null,
     * "mother_maiden_name": null,
     * "guarantor": null,
     * "guarantor_contact": null,
     * "country_of_residence_id": null,
     * "country_of_origin_id": null,
     * "state_id": null,
     * "lga_id": null,
     * "city": null,
     * "created_at": "2021-06-15T09:07:48.000000Z",
     * "updated_at": "2021-06-15T09:07:48.000000Z",
     * "user": {
     * "id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "name": "Lubem Tser",
     * "email": "enginlubem@ymail.com",
     * "role_id": null,
     * "email_verified_at": null,
     * "transaction_pin": "1111",
     * "bvn": null,
     * "phone": "08034567890",
     * "gLocatorID": null,
     * "verified": null,
     * "verified_otp": null,
     * "image": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870ad472ea.jpg",
     * "dob": null,
     * "sex": null,
     * "withdrawal_limit": 100000,
     * "shutdown_level": 0,
     * "account_type_id": 1,
     * "created_at": "2021-05-25T19:19:28.000000Z",
     * "updated_at": "2021-06-15T09:19:41.000000Z",
     * "image_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870ad472ea.jpg"
     * },
     * "state": null,
     * "lga": null,
     * "residence": null,
     * "origin": null
     * },
     * {
     * "id": "7edc688c-8459-44a1-95d0-2d4287aeae1e",
     * "user_id": "19802b9f-5a0a-4987-bee1-b7ba3461c475",
     * "first_name": null,
     * "last_name": null,
     * "middle_name": null,
     * "address": null,
     * "home_address": null,
     * "proof_of_address_url": null,
     * "id_card_number": null,
     * "id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c870f7bb964.jpg",
     * "next_of_kin": null,
     * "next_of_kin_contact": null,
     * "mother_maiden_name": null,
     * "guarantor": null,
     * "guarantor_contact": null,
     * "country_of_residence_id": null,
     * "country_of_origin_id": null,
     * "state_id": null,
     * "lga_id": null,
     * "city": null,
     * "created_at": "2021-06-15T09:20:59.000000Z",
     * "updated_at": "2021-06-15T09:20:59.000000Z",
     * "user": {
     * "id": "19802b9f-5a0a-4987-bee1-b7ba3461c475",
     * "name": "Super Admin",
     * "email": "superadmin@localhost",
     * "role_id": null,
     * "email_verified_at": null,
     * "transaction_pin": "1122",
     * "bvn": null,
     * "phone": "08034567890",
     * "gLocatorID": null,
     * "verified": null,
     * "verified_otp": null,
     * "image": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870f9bdd73.jpg",
     * "dob": null,
     * "sex": null,
     * "withdrawal_limit": 100000,
     * "shutdown_level": 0,
     * "account_type_id": 1,
     * "created_at": "2021-05-25T19:21:37.000000Z",
     * "updated_at": "2021-06-15T09:20:59.000000Z",
     * "image_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870f9bdd73.jpg"
     * },
     * "state": null,
     * "lga": null,
     * "residence": null,
     * "origin": null
     * }
     * ],
     * "message": "all know your customers details obtained successfully",
     * "status": "success"
     * }
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (count(request()->query()) > 0) {
            $kycs = Kyc::on('mysql::read')->where(request()->query())->with(['user', 'state', 'lga', 'residence', 'origin'])->get();
        }else {
            $kycs = Kyc::on('mysql::read')->with(['user', 'state', 'lga', 'residence', 'origin'])->get();
        }
        return $this->sendResponse($kycs, 'all know your customers details obtained successfully');
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
     * Store a newly created kyc in storage.
     *
     * @bodyParam user_id integer required The ID of the user from users table.
     * @bodyParam first_name string The first name of the user.
     * @bodyParam middle_name string The middle name of the user.
     * @bodyParam last_name string The last name of the user.
     * @bodyParam sex string The sex status of the user. To be saved in users table
     * @bodyParam dob string The date of birth of the user. To be saved in users table
     * @bodyParam profile_pic file The profile picture of the user. To be saved in users table
     * @bodyParam next_of_kin string The name of the next of kin.
     * @bodyParam next_of_kin_contact string The phone number of the next of kin.
     * @bodyParam guarantor string The guarantor's name of the kyc.
     * @bodyParam guarantor_contact string The guarantor's phone number of the kyc.
     * @bodyParam id_card_number string The ID card number of the kyc.
     * @bodyParam card_file file. The ID card file to be uploaded of the kyc.
     * @bodyParam id_card_type_id integer. The ID card type to be selected from id cards type table.
     * @bodyParam state_id integer The state ID of the loan account holder from states table.
     * @bodyParam lga_id integer The local govt area ID of the loan account holder from lgas table.
     * @bodyParam mother_maiden_name string The maiden name of the kyc's mother i.e her father's name.
     * @bodyParam address string The address of the kyc, this may be different from the home address.
     * @bodyParam home_address string The home address of the kyc.
     * @bodyParam address string The address of the loan account holder.
     * @bodyParam address_file file The evidence of address file to upload of the loan account holder
     * @bodyParam passport_photo file The passport photo file to be uploaded of the kyc.
     * @bodyParam country_of_residence_id integer The country of residence of the kyc to be selected from countries table.
     * @bodyParam country_of_origin_id integer The country of origin of the kyc to be selected from countries table.
     * @bodyParam city string The name of the city of the kyc.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": null,
     * "last_name": null,
     * "middle_name": null,
     * "address": null,
     * "passport_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/passports/70f67aw4589gi.jpg",
     * "home_address": null,
     * "proof_of_address_url": null,
     * "id_card_number": null,
     * "id_card_type_id": 1,
     * "id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
     * "next_of_kin": null,
     * "next_of_kin_contact": null,
     * "mother_maiden_name": null,
     * "guarantor": null,
     * "guarantor_contact": null,
     * "country_of_residence_id": 161,
     * "country_of_origin_id": 154,
     * "state_id": 9,
     * "lga_id": 164,
     * "city": null,
     * "created_at": "2021-06-15T09:07:48.000000Z",
     * "updated_at": "2021-06-15T09:07:48.000000Z",
     * },
     * "message": "user information retrieved successfully",
     * "status": "success"
     * }
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'first_name' => 'string|max:60',
            'last_name' => 'string|max:60',
            'card_file' => 'file:max:2000', //max size 2Mb
            'address_file' => 'file|max:2000',
            'profile_pic' => 'file|max:2000',
            'passport_photo' => 'file|max:2000',
        ]);
        $data = $request->except(['bvn', 'phone', 'dob', 'sex', 'profile_pic', 'address_file', 'card_file', 'passport_photo']);

        if (!$this->ownsRecord($request->get('user_id'))) {
            return $this->sendResponse(null, 'This account does not belong to you. You must log in to perform this operation');
        }
        //check if address file is uploaded
        if ($request->hasFile('address_file')) {
            $data['proof_of_address_url'] = $this->uploadAwsFile($request->file('address_file'), 'transave/users/address');
        }
        if ($request->hasFile('card_file')) {
            $data['id_card_url'] = $this->uploadAwsFile($request->file('card_file'), 'transave/users/id-cards');
        }
        if ($request->hasFile('passport_photo')) {
            $data['passport_url'] = $this->uploadAwsFile($request->file('passport_photo'), 'transave/users/passports');
        }

        $user = User::on('mysql::read')->find($request->get('user_id'));
        if ($request->hasFile('profile_pic')) {
            if ($user->image ) {
                $this->deleteFromAws($user->image);
            }
            $image = $this->uploadAwsFile($request->file('profile_pic'), 'transave/users/profiles');
            $user->image = $image;
            $user->save();
        }
        //update user if fields are present
        $inputs = $request->only(['dob', 'sex']);
        if (!empty($inputs)) {
            $user->fill($inputs)->save();
        }

        $kyc = Kyc::on('mysql::write')->create($data);
        $this->updateUserAccountType($user->id);

        if ($kyc) {
            return $this->sendResponse($kyc, 'user information created successfully');
        }
        return $this->sendError('error in creating user information');
    }

    /**
     * Display the specified kyc.
     * @urlParam id integer required The id of the kyc or User id.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": null,
     * "last_name": null,
     * "middle_name": null,
     * "address": null,
     * "home_address": null,
     * "proof_of_address_url": null,
     * "id_card_number": null,
     * "id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
     * "next_of_kin": null,
     * "next_of_kin_contact": null,
     * "mother_maiden_name": null,
     * "guarantor": null,
     * "guarantor_contact": null,
     * "country_of_residence_id": null,
     * "country_of_origin_id": null,
     * "state_id": null,
     * "lga_id": null,
     * "city": null,
     * "created_at": "2021-06-15T09:07:48.000000Z",
     * "updated_at": "2021-06-15T09:07:48.000000Z",
     * "user": {
     * "id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "name": "Lubem Tser",
     * "email": "enginlubem@ymail.com",
     * "role_id": null,
     * "email_verified_at": null,
     * "gLocatorID": null,
     * "verified": null,
     * "verified_otp": null,
     * "image": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870ad472ea.jpg",
     * "dob": null,
     * "sex": null,
     * "withdrawal_limit": 100000,
     * "shutdown_level": 0,
     * "account_type_id": 1,
     * "created_at": "2021-05-25T19:19:28.000000Z",
     * "updated_at": "2021-06-15T09:19:41.000000Z",
     * "image_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/profiles/60c870ad472ea.jpg"
     * },
     * "state": null,
     * "lga": null,
     * "origin": null,
     * "residence": null
     * },
     * "message": "user information retrieved successfully",
     * "status": "success"
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kyc = Kyc::on('mysql::read')->whereId($id)->orWhere('user_id', $id)->with(['user', 'state', 'lga', 'origin', 'residence'])->first();
        if ($kyc) {
            return $this->sendResponse($kyc, 'user information retrieved successfully');
        }
        return $this->sendResponse(null, 'unable to get user information');
    }

    /**
     * Send a request to admin for editing a kyc.
     *
     * @urlParam id integer required The ID of the kyc or User Id.
     * @bodyParam content string The content of the message request.
     *
     * @response {
     * "success": true,
     * "data": null,
     * "message": "your message has been sent successfully",
     * "status": "success"
     * }
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, $id)
    {
        $message['title'] = 'Request KYC Edit';
        if ($request->has('content')) {
            $this->validate($request, [
                'content' => 'required|string',
            ]);
            $message['content'] = $request->get('content');
        }else {
            $message['content'] = '<div>I wish to edit my kyc. This has become absolutely necessary due to changes I have to make on my account. Please treat this request as urgent.</div>';
        }

        $kyc = Kyc::on('mysql::read')->where('id', $id)->orWhere('user_id', $id)->first();
        $user = $kyc->user;
        if ($user) {
            AccountRequest::on('mysql::write')->create([
                'user_id' => $user->id,
                'account_type_id' => $user->account_type_id,
                'request_type' => AccountRequestType::EDIT,
                'content' => $message['content'],
            ]);
            Mail::to('support@transave.com.ng')->send(new KycEditMail($user, $message));
            return $this->sendResponse(null, 'your message has been sent successfully');
        }
        return $this->sendError('unable to send your request at the moment');
    }

    /**
     * Update the specified kyc in storage.
     *
     * @urlParam id integer required The ID of the kyc.
     * @bodyParam user_id integer required The ID of the user from users table.
     * @bodyParam first_name string The first name of the user.
     * @bodyParam middle_name string The middle name of the user.
     * @bodyParam last_name string The last name of the user.
     * @bodyParam sex string The sex status of the user. To be saved in users table
     * @bodyParam dob string The date of birth of the user. To be saved in users table
     * @bodyParam profile_pic file The profile picture of the user. To be saved in users table
     * @bodyParam next_of_kin string The name of the next of kin.
     * @bodyParam next_of_kin_contact string The phone number of the next of kin.
     * @bodyParam guarantor string The guarantor's name of the kyc.
     * @bodyParam guarantor_contact string The guarantor's phone number of the kyc.
     * @bodyParam id_card_number string The ID card number of the kyc.
     * @bodyParam card_file file. The ID card file to be uploaded of the kyc.
     * @bodyParam id_card_type_id integer. The ID card type to be selected from id cards type table.
     * @bodyParam state_id integer The state ID of the loan account holder from states table.
     * @bodyParam lga_id integer The local govt area ID of the loan account holder from lgas table.
     * @bodyParam mother_maiden_name string The maiden name of the kyc's mother i.e her father's name.
     * @bodyParam address string The address of the kyc, this may be different from the home address.
     * @bodyParam home_address string The home address of the kyc.
     * @bodyParam address string The address of the loan account holder.
     * @bodyParam address_file file The evidence of address file to upload of the loan account holder
     * @bodyParam passport_photo file The passport photo file to be uploaded of the kyc.
     * @bodyParam country_of_residence_id integer The country of residence of the kyc to be selected from countries table.
     * @bodyParam country_of_origin_id integer The country of origin of the kyc to be selected from countries table.
     * @bodyParam city string The name of the city of the kyc.
     *
     * @response {
     * "success": true,
     * "data": {
     * "id": "7495768f-22e4-4f7b-bf27-c6a52f895ab7",
     * "user_id": "2248e4fc-97b0-48e7-8ae3-b660e11cc499",
     * "first_name": null,
     * "last_name": null,
     * "middle_name": null,
     * "address": null,
     * "passport_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/passports/70f67aw4589gi.jpg",
     * "home_address": null,
     * "proof_of_address_url": null,
     * "id_card_number": null,
     * "id_card_type_id": 1,
     * "id_card_url": "https://slait-aws3-storage.s3.amazonaws.com/transave/users/id-cards/60c86de2598fe.jpg",
     * "next_of_kin": null,
     * "next_of_kin_contact": null,
     * "mother_maiden_name": null,
     * "guarantor": null,
     * "guarantor_contact": null,
     * "country_of_residence_id": 161,
     * "country_of_origin_id": 154,
     * "state_id": 9,
     * "lga_id": 164,
     * "city": null,
     * "created_at": "2021-06-15T09:07:48.000000Z",
     * "updated_at": "2021-06-15T09:07:48.000000Z",
     * },
     * "message": "user information retrieved successfully",
     * "status": "success"
     * }
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'string|max:60',
            'last_name' => 'string|max:60',
//            'card_file' => 'file|max:2000', //max size 2Mb
//            'address_file' => 'file|max:2000',
//            'profile_pic' => 'file|max:2000',
//            'passport_photo' => 'file|max:2000',
        ]);

        $kyc = Kyc::on('mysql::read')->where('id', $id)->orWhere('user_id', $id)->first();
        if (!$kyc) {
            return $this->sendResponse(null, 'kyc not found');
        }
        if ($kyc->is_completed) {
            return $this->sendResponse(null, 'you have completed your kyc and need admin permission to edit');
        }

        if (!$this->ownsRecord($kyc->user_id)) {
            return response()->json(['message'=>'You dont have permission to do this operation. ensure you are logged in'], 405);
        }

        //check if address file is uploaded
        if ($request->hasFile('address_file')) {
            if($kyc->proof_of_address_url) {
                $this->deleteFromAws($kyc->proof_of_address_url);
            }
            $kyc->proof_of_address_url = $this->uploadAwsFile($request->file('address_file'), 'transave/users/address');
            $kyc->save();
        }
        if ($request->hasFile('card_file')) {
            if ($kyc->id_card_url) {
                $this->deleteFromAws($kyc->id_card_url);
            }
            $kyc->id_card_url = $this->uploadAwsFile($request->file('card_file'), 'transave/users/id-cards');
            $kyc->save();
        }
        if ($request->hasFile('passport_photo')) {
            if ($kyc->passport_url) {
                $this->deleteFromAws($kyc->passport_url);
            }
            $kyc->passport_url = $this->uploadAwsFile($request->file('passport_photo'), 'transave/users/passports');
            $kyc->save();
        }

        $user = User::on('mysql::read')->find($kyc->user_id);
        if ($request->hasFile('profile_pic')) {
            if ($user->image) {
                $this->deleteFromAws($user->image);
            }
            $image = $this->uploadAwsFile($request->file('profile_pic'), 'transave/users/profiles');
            $user->image = $image;
            $user->save();
        }

        $data = $request->except(['bvn', 'phone', 'dob', 'sex', 'user_id', 'is_completed', 'address_file', 'passport_photo', 'card_file', 'profile_pic']);

        if (!empty($data)) {
            $kyc->fill($data)->save();
        }

        $inputs = $request->only(['dob', 'sex']);

        if (!empty($inputs)) {
            $user->fill($inputs)->save();
        }
        $kyc_data = $this->updateUserAccountType($user->id);
        if ($kyc_data) {
            $kyc->is_completed = 1;
            $kyc->save();
        }

        return $this->sendResponse(Kyc::find($id), 'user details updated successfully');
    }

    /**
     * Check the status of a specified kyc.
     * @urlParam id integer required The ID of the kyc or User Id.
     * @response {
     * "success": true,
     * "data": {
     * "state": true
     * },
     * "message": "completed",
     * "status": "success",
     * }
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function check($id)
    {
        $kyc = Kyc::on('mysql::read')->where('id', $id)->orWhere('user_id', $id)->first();
        $status = $this->updateUserAccountType($kyc->user_id);
        $data['state'] = (boolean)$status;
        if ($status) {
            return $this->sendResponse($data, 'completed');
        }
        return $this->sendResponse($data, 'uncompleted');
    }

    /**
     * Show the percentage of filled entries in a kyc
     *
     * @urlParam id integer required The ID of the kyc or User Id.
     * @response {
     * "success": true,
     * "data": 27.77777777777778,
     * "message": "percentage completed returned successfully",
     * "status": "success"
     * }
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function percentage($id)
    {
        $kyc = Kyc::on('mysql::read')->where('id', $id)->orWhere('user_id', $id)->first();
        //counters
        $total = 0;
        $null = 0;
        // using $kyc->getFillable() for return all fields from the model
        foreach ($kyc->getFillable() as $key => $row) {
            //exclude data were kyc not compulsory
            if($row != 'home_address' && $row != 'middle_name' && $row != 'is_completed')
            {
                //count all fields
                $total = $total + 1;
                //count fields where value is null
                if($kyc->$row != null)
                {
                    $null++;
                }
            }
        }
        //calculate percentage
        $percentage = $null / $total * 100;
        return $this->sendResponse($percentage, 'percentage completed returned successfully');
    }

    /**
     * Remove the specified Kyc from storage.
     * @urlParam id integer required The ID of the kyc.
     * @response {
     * "success": true,
     * "data": {
     *
     * },
     * "message": "user details deleted successfully",
     * "status": "success",
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kyc = Kyc::on('mysql::read')->where('id', $id)->orWhere('user_id', $id)->first();
        if ($kyc->proof_of_address_url) {
            $this->deleteFromAws($kyc->proof_of_address_url);
        }
        if ($kyc->id_card_url) {
            $this->deleteFromAws($kyc->id_card_url);
        }
        if ($kyc->passport_url) {
            $this->deleteFromAws($kyc->passport_url);
        }

        if ($kyc->delete()) {
            return $this->sendResponse(null, 'user kyc details deleted successfully');
        }
        return $this->sendError('unable to delete user kyc details');
    }
}
