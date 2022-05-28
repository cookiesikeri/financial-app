<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Pos;
use App\Models\PosRequest;
use App\Models\PosTransaction;
use App\Models\PosVendor;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ManagesCommission;
use App\Traits\ManagesResponse;
use App\Traits\SendSms;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator as ValidationValidator;
use Validator;

class POSController extends Controller
{
    use ManagesResponse, SendSms, ManagesCommission;

    public function POSManagement(Request $request)
    {
       try{
           $userId = $request->input('user_id');
           if(!empty($userId)) {
               $posRequests =  PosRequest::where('user_id',$userId)->first();
               $message = 'POS request successfully fetched';

               return $this->sendResponse($posRequests,$message);
           } else {
               $posRequests =  PosRequest::all();
               $message = 'POS request successfully fetched';

               return $this->sendResponse($posRequests,$message);
           }
       } catch (\Exception $e) {
           return $this->sendError($e->getMessage(),[],500);
       }
    }

    public function fetchAllPOSRequest(Request $request)
    {
        try{
            $userId = $request->input('user_id');
            if(!empty($userId)) {
                $posRequests =  PosRequest::where('user_id',$userId)->first();
                $message = 'POS request successfully fetched';

                return $this->sendResponse($posRequests,$message);
            } else {
                $posRequests =  PosRequest::all();
                $message = 'POS request successfully fetched';

                return $this->sendResponse($posRequests,$message);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchMerchantTransactions()
    {
        $token = $this->baseUrl();
        if($token['status']) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token['data']['token']
            ])->get(env('LUX_URL').'transaction');

            $message = 'POS transactions successfully fetched';

            return $this->sendResponse($response['data'],$message);
        }
    }

    public function merchantTerminals($id)
    {
        try{
            $merchant =  Pos::where('user_id',$id)->get();
            $message = 'POS request successfully fetched';

            return $this->sendResponse($merchant,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchSingleMerchantTransactions(Request $request)
    {
        try {
            $id = $request->input('user_id');
            $user = '';
            if(!empty($id)) {
                $user = User::findOrFail($id)->pos;
            } else {
                $user = Auth::user()->pos;
            }

//        $user = !empty($terminalId) ? $terminalId : Auth::user()->pos->terminalId;
            $transaction = PosTransaction::where('terminalId',$user->terminalId)
                ->orderBy('created_at','desc')
                ->get();
            $message = 'POS transactions successfully fetched';

            return $this->sendResponse($transaction,$message);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()],404);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    public function fetchTerminalStatistics(Request $request)
    {
        try{
            $id = $request->input('user_id');
            if(!empty($id)){
                 $user = User::findOrFail($id);
//                return $user = User::where('id',$id)->with('wal')->first();
                $transactions = PosTransaction::where('wallet_id',$user->wallet->id)->get()->count();
                $amount = PosTransaction::where('wallet_id',$user->wallet->id)
                    ->sum('amount');
                $terminals = Pos::where('user_id',$id)->get()->count();

                $data = [
                    'transactions' => $transactions,
                    'terminals' => $terminals,
                    'balance' => $user->wallet->balance,
                    'user' => $user,
                    'transaction_amount' => $amount
                ];
                $message = 'POS statistcs successfully fetched';
                return $this->sendResponse($data,$message);
            } else {
                $transactions = PosTransaction::get()->count();
                $posRequest = PosRequest::get()->count();
                $unmapped = Pos::where('user_id',null)->get()->count();
                $mapped = Pos::where('user_id','!=',null)->get()->count();
                $terminals = Pos::get()->count();

                $data = [
                    'transactions' => $transactions,
                    'terminals' => $terminals,
                    'assigned' => $mapped,
                    'unassigned' => $unmapped,
                    'requestedPOS' => $posRequest
                ];
                $message = 'POS statistcs successfully fetched';
                return $this->sendResponse($data,$message);
            }
        } catch (\Exception $e){

        }
    }

    public function terminalTransactionHook(Request $request)
    {
        $terminalId = $request->input('terminalId');
        $reference = $request->input('reference');
        $amount = $request->input('amount');
        $transactionDate = $request->input('transactionDate');
        $hash = $request->input('hash');

        $data = [
            'terminalId' => $terminalId,
            'reference' => $reference,
            'amount' => $amount,
            'transactionDate' => $transactionDate,
        ];

        if(env('LUX_SECRET_KEY').$reference.$amount.$transactionDate === $hash){
            $key = $terminalId.':'.$reference.':'.$amount.':'.$transactionDate;
            $validatingRequest = Cache::get($key);

            if(!empty($validatingRequest)){
                return $this->sendResponse('Processing transaction','Transaction has been received and is been processed');
            }
            $pos = Pos::where('terminalId',$terminalId)->first();
            if(!empty($pos)){
                $this->createIdempotent($key,$data);

                $wallet = Wallet::on('mysql::write')->where('user_id',$pos->user_id)->first();
                $wallet->update([
                    'balance' => (double) $wallet->balance + (double) $amount
                ]);
                $input = $request->all();
                $input['wallet_id'] = $wallet->id;
                PosTransaction::create($input);

                $message = 'POS notification successfully logged';

                return $this->sendResponse([],$message);
            }
        }
        $message = 'POS notification could not be logged';

        return $this->sendResponse([],$message);


    }

    private function baseUrl()
    {
        $response = Http::post(env('LUX_URL').'merchant/login',[
            'email' => env('LUX_USERNAME'),
            'password' => env('LUX_PASSWORD')
        ]);

        return $response;
    }

    public function fetchUnLinkPosTerminal()
    {
        try{
            $pos =  Pos::on('mysql::read')->where('user_id',null)->get();
            $message = 'POS request successfully fetched';

            return $this->sendResponse($pos,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchLinkPosTerminal()
    {
        try{
            $pos =  Pos::on('mysql::read')
                ->with('user')
                ->where('user_id','!=',null)->get();
            $message = 'POS request successfully fetched';

            return $this->sendResponse($pos,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchAllPosTerminal()
    {
        try{
            $pos =  Pos::on('mysql::read')->with('user')
                ->join('pos_requests','pos_requests.user_id','pos.user_id')
                ->get();
            $message = 'POS request successfully fetched';

            return $this->sendResponse($pos,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchUserPosTransactions()
    {
        $user = Auth::user();
        $posRequest = PosRequest::where('user_id',$user->id);

        //Eloquent relationshtip
        try{
            $pos =  Pos::on('mysql::read')->get();
            $message = 'POS request successfully fetched';

            return $this->sendResponse($pos,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function fetchSingleUserTerminals($id)
    {
        //Eloquent relationshtip
        try{
            $pos =  Pos::on('mysql::read')
                ->where('user_id',$id)
                ->get();

            $message = 'POS terminal successfully fetched';

            return $this->sendResponse($pos,$message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),[],500);
        }
    }

    public function posStatus(Request $request)
    {
        $terminalId = $request->input('termainalId');
        $status = $request->input('status');

        $pos = Pos::on('mysql::write')->where('terminalId',$terminalId)->first();
        if(!empty($pos)){
            $token = $this->baseUrl();
            if($token['status']) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$token['data']['token']
                ])->put(env('LUX_URL')."pos/$terminalId",[
                    'status' => $status
                ]);

                if($response['status']){
                    $pos->update([
                        'status' => $status
                    ]);
                    return $this->sendResponse([],'POS Terminal Successfully updated');
                }
            }
        }
    }

    public function assignUserToPos(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'pos_id' => 'required',
        ]);

        $posId = $request->input('pos_id');
        $userId = $request->input('user_id');

        if($validator->fails()){
            return $this->sendError('Invalid request',$validator->errors(),422);
        }

        $pos = Pos::on('mysql::write')->where('id',$posId)->first();
        $pos_request = PosRequest::on('mysql::write')->where('user_id',$userId)->first();

        if(!empty($pos)) {
            $pos->update([
                'user_id' => $userId,
                'status' => 'active'
            ]);
            $pos_request->update([
                'status' => 'processed'
            ]);
        }
        return $this->sendResponse([],'POS Terminal Successfully updated');
    }

    public function createPosTerminal(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pos_type' => 'required',
            'description' => 'required',
            'terminalId' => 'required',
            'serialNumber' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Invalid request',$validator->errors(),422);
        }

//        $token = $this->baseUrl();
//        if($token['status']) {
//            $response = Http::withHeaders([
//                'Authorization' => 'Bearer '.$token['data']['token']
//            ])->post(env('LUX_URL').'pos',[
//                'ptsp' => $request->input('pos_type'),
//                'terminalId' => $request->input('terminalId'),
//                'serialNumber' => $request->input('serialNumber'),
//                'bankMerchantId' => '2033LAGPOOO6211',
//            ]);
//
//            if($response['status']) {
                Pos::on('mysql::write')->updateOrCreate([
                    'terminalId' => $request->input('terminalId'),
                    'serialNumber' => $request->input('serialNumber'),
                ],$request->all());

                return $this->sendResponse([],'POS Terminal Successfully created');
//            }
//            return $this->sendError($response['message'],[],403);
//        } else {
//            return $this->sendError('Couldnt create POS',[],403);
//        }

    }

    public function posVendor(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Invalid Input',$validator->errors(),422);
        }

        PosVendor::on('mysql::write')->create($request->all());

        return $this->sendResponse([],'POS Vendor Successfully created');
    }

    private function createIdempotent($key,$data)
    {
//        Route::post('create/terminal', [\App\Http\Controllers\POSController::class,'createPosTerminal']);

        Cache::store('file')->put($key,$data,300);
    }

    public function itexTerminalTransactionHook(Request $request)
    {
        $terminalId = $request->input('terminalId');
        $amount = $request->input('amount')/100;
        $authCode = $request->input('authCode');
        $transactionDate = $request->input('transactionDate');
        $reversal = $request->input('reversal');
        $input = $request->all();
        $input['transactionDate'] = $request->input('transactionTime');
        $input['pan'] = $request->input('PAN');
        $input['stan'] = $request->input('STAN');
        $input['reference'] = $authCode;
        $input['statusDescription'] = $request->input('responseDescription');

        $data = [
            'terminalId' => $terminalId,
            'amount' => $amount,
            'authCode' => $authCode,
            'transactionDate' => $transactionDate,
        ];

        $key = $terminalId.':'.$authCode.':'.$amount.':'.$transactionDate;
        $validatingRequest = Cache::get($key);

        if(!empty($validatingRequest)){
            return $this->sendResponse('Processing transaction','Transaction has been received and is been processed');
        }

        $pos = Pos::where('terminalId',$terminalId)->first();
        if(!empty($pos)){
            $this->createIdempotent($key,$data);

            $commission = Commission::where('type','pos')->first();
            if(!empty($commission)){
                $wallet = Wallet::on('mysql::write')->where('user_id',$pos->user_id)->first();
                $commission_value = $amount* ($commission->percent);
                $amount_commission_charge = $amount - $commission_value;
                $input['amount'] = $amount;
                $input['commission_amount'] = $commission_value;
                if(empty($wallet)){
                    $input['wallet_id'] = "127f6c56-5d1f-46d6-aa5c-ff3f07122e3b";
                    $this->secureCommission($commission_value);
                    PosTransaction::create($input);
                    Http::post(env('VFD_HOOK_URL'),[
                        'text' => "UnAssigned POS holder was credited with amount of NGN$amount, and commission charge was NGN$commission_value",
                        'username' => 'POS Controller',
                        'icon_emoji' => ':boom:',
                        'channel' => env('SLACK_CHANNEL_PT')
                    ]);
                    return $this->sendResponse('Processing transaction','Client not found');
                }
                $input['wallet_id'] = $wallet->id;

                if($reversal === "false") {
                    $wallet->update([
                        'balance' => (double) $wallet->balance + (double) $amount_commission_charge
                    ]);
                    $this->secureCommission($commission_value);
                    PosTransaction::create($input);
                    $message = "POS transaction credit occured on your terminal, with amount of NGN".number_format($amount).", and commission charge was NGN$commission_value. Your new balance is NGN".number_format($wallet->balance);
                    $this->sendSms($wallet->user->phone,$message);
                    $messages = 'POS notification successfully logged';

                    Http::post(env('VFD_HOOK_URL'),[
                        'text' => $wallet->user->name." a POS holder was credited with amount of NGN$amount, and commission charge was NGN$commission_value",
                        'username' => 'POS Controller',
                        'icon_emoji' => ':boom:',
                        'channel' => env('SLACK_CHANNEL_PT')
                    ]);
                    //save
                    return $this->sendResponse([],$messages);
                } else {
                    $input['reversal'] = $reversal;
                    PosTransaction::create($input);
                    $message = 'Transaction Reversed';
                    return $this->sendResponse([],$message);
                }
            }
        }
        $message = 'POS notification response could not be logged, Couldnt find Terminal';

        return $this->sendResponse([],$message);
    }
}
