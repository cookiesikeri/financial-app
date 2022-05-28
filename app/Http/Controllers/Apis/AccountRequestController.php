<?php

namespace App\Http\Controllers\Apis;

use App\Enums\AccountRequestAction;
use App\Enums\AccountRequestType;
use App\Http\Controllers\Controller;
use App\Mail\KycEditMail;
use App\Models\AccountRequest;
use App\Models\AccountType;
use App\Models\CustomerValidation;
use App\Models\Kyc;
use App\Models\User;
use App\Traits\ManagesResponse;
use App\Traits\ManagesUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @group User activities
 *
 * APIs for handling a user activities
 */
class AccountRequestController extends Controller
{
    use ManagesResponse, ManagesUsers;

    public function index()
    {
        $type = request()->query('type');
        if (request()->query('search')) {
            $search = request()->query('search');
            $requests = AccountRequest::on('mysql::read')->with(['user', 'accounttype'])->where('request_type', $type)->where(function ($query) use ($search) {
                $query->whereIn('account_type_id', $this->accountFromName($search))
                    ->orWhereIn('user_id', $this->userFromName($search));
            })->orderBy('created_at', 'desc')->paginate(10);
        }elseif (request()->query('start') && !request()->query('end')) {
            $startDate = Carbon::parse(request()->query('start'));
            $requests = AccountRequest::on('mysql::read')->where('request_type', $type)->where('created_at', '>=', $startDate)
                ->with(['user', 'accounttype'])->orderBy('created_at', 'desc')->paginate(10);
        }elseif(!request()->query('start') && request()->query('end')) {
            $endDate = Carbon::parse(request()->query('end'));
            $requests = AccountRequest::on('mysql::read')->where('request_type', $type)->where('created_at', '<=', $endDate)
                ->with(['user', 'accounttype'])->orderBy('created_at', 'desc')->paginate(10);
        }elseif (request()->query('start') && request()->query('end')) {
            $startDate = Carbon::parse(request()->query('start'));
            $endDate = Carbon::parse(request()->query('end'));
            $requests = AccountRequest::on('mysql::read')->where('request_type', $type)->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->with(['user', 'accounttype'])->orderBy('created_at', 'desc')->paginate(10);
        }else {
            $requests = AccountRequest::where('request_type', $type)->with(['user', 'accounttype'])->orderBy('created_at', 'desc')->paginate(10);
        }

        return $this->sendResponse($requests, 'all account upgrade requests fetched successfully');
    }

    public function upgrade($id)
    {

        if ($this->upgradeUserAccount($id)) {
            $request = AccountRequest::on('mysql::read')->find($id);
            return $this->sendResponse($request, 'account request treated successfully');
        }
        return $this->sendError('account not updated');
    }

    public function show($id)
    {
        $request = AccountRequest::on('mysql::read')->with(['accounttype'])->where('id', $id)->orWhere('user_id', $id)->first();
        $request['user'] = User::on('mysql::read')->where('id', $request->user_id)->with(['accounttype', 'customer_verification'])->first();
        $request['kyc'] = Kyc::on('mysql::read')->where('user_id', $request->user_id)->with(['state', 'lga', 'residence', 'origin', 'idcardtype'])->first();

        return $this->sendResponse($request, 'request details retrieved successfully');
    }

    public function count() {

        $data['upgrade'] = count(AccountRequest::where('request_type', AccountRequestType::UPGRADE)->get());
        $data['edit'] = count(AccountRequest::where('request_type', AccountRequestType::EDIT)->get());
        $data['shutdown'] = count(AccountRequest::where('request_type', AccountRequestType::SHUTDOWN)
            ->orWhere('request_type', AccountRequestType::UNBLOCK)->get());
        $data['users'] = count(User::all());

        return $this->sendResponse($data, 'request retrieved successfully');
    }

    /**
     * Send a request to admin for shutting down account
     *
     * @urlParam id integer required The ID of the user.
     * @bodyParam content string The content of the message request.
     *
     * @response {
     * "success": true,
     * "data": null,
     * "message": "shutdown request has been sent successfully",
     * "status": "success"
     * }
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function shutdownRequest(Request $request, $id)
    {
        $message['title'] = 'Account Shutdown Request';

        $user = User::on('mysql::read')->find($id);
        if ($request->has('content')) {
            $this->validate($request, [
                'content' => 'required|string',
            ]);
            $message['content'] = $request->get('content');
        }else {
            $message['content'] = '<p>I wish to shutdown my account temporary due to personal reasons</p>';
        }

        $accountRequest = AccountRequest::on('mysql::write')->create([
            'user_id' => $user->id,
            'account_type_id' => $user->account_type_id,
            'request_type' => AccountRequestType::SHUTDOWN,
            'content' => $message['content'],
        ]);

        if ($accountRequest) {
            Mail::to('support@transave.com.ng')->send(new KycEditMail($user, $message));
            return $this->sendResponse(null, 'shutdown request has been sent successfully');
        }
        return $this->sendError('unable to send your request at the moment');
    }

    /**
     * Send a request to admin for unblocking account by the user
     *
     * @urlParam id integer required The ID of the user.
     * @bodyParam content string The content of the message request.
     *
     * @response {
     * "success": true,
     * "data": null,
     * "message": "unblock request has been sent successfully",
     * "status": "success"
     * }
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function activateRequest(Request $request, $id)
    {
        $message['title'] = 'Account Unblocking Request';

        $user = User::on('mysql::read')->find($id);
        if ($request->has('content')) {
            $this->validate($request, [
                'content' => 'required|string',
            ]);
            $message['content'] = $request->get('content');
        }else {
            $message['content'] = '<p>I wish to reactivate my account to enable me carry out transactions</p>';
        }

        $accountRequest = AccountRequest::on('mysql::write')->create([
            'user_id' => $user->id,
            'account_type_id' => $user->account_type_id,
            'request_type' => AccountRequestType::UNBLOCK,
            'content' => $message['content'],
        ]);

        if ($accountRequest) {
            Mail::to('support@transave.com.ng')->send(new KycEditMail($user, $message));
            return $this->sendResponse(null, 'unblock request has been sent successfully');
        }
        return $this->sendError('unable to send your request at the moment');
    }

    public function destroy($id)
    {
        $account = AccountRequest::where('user_id', $id)->orWhere('id', $id)->first();
        if ($account) {
            $account->delete();
            return $this->sendResponse(null, 'account request deleted successfully');
        }
        return $this->sendError('unable to delete account request');
    }

    private function userFromName($name)
    {
        return User::where('name', 'like', '%'.$name.'%')->get()->toArray();
    }

    private function accountFromName($name)
    {
        return AccountType::where('name', 'like', '%'.$name.'%')->get()->toArray();
    }

    private function statusFromName($name)
    {
        if (strtolower($name) === 'pending') {
            return 0;
        }elseif (strtolower($name) === 'approved') {
            return 1;
        }else {
            return '';
        }
    }

}
