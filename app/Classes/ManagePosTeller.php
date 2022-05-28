<?php


namespace App\Classes;


use App\Models\AgentTeller;
use App\Models\Commission;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Http;
use Validator;

class ManagePosTeller
{
    public $teller,$commission,$transactions,$wallet;

    public string $url;
    public string $access_token;
    public string $wallet_id;

    public function __construct()
    {
        $this->teller = new AgentTeller();
        $this->commission = new Commission();
        $this->transactions = new WalletTransaction();
        $this->wallet = new Wallet();
        $this->url = config('vfd.url');
        $this->access_token = config('vfd.key');
        $this->wallet_id = config('vfd.wallet_id');
    }

    public function createAgentTeller($data)
    {
        $validator = Validator::make($data->all(),[
           'name' => 'required',
           'location' => 'required',
           'user_id' => 'required',
           'terminalID' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],422);
        }
        $this->teller->updateOrCreate([
            'user_id' => $data['user_id'],
            'terminalID' => $data['terminalID'],
        ],[
            'user_id' => $data['user_id'],
            'terminalID' => $data['terminalID'],
            'location' => $data['location'],
            'name' => $data['name']
        ]);

        return response()->json(['message' => 'Agent successfully created'],201);
    }

    public function fetchAllCommission()
    {
        $commission = $this->commission->all();

        return response()->json(['message' => 'Commission successfully set','commission' => $commission],201);
    }

    public function createCommission($data)
    {
        $validator = Validator::make($data->all(),[
            'type' => 'required',
            'percent' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],422);
        }
        $this->commission->updateOrCreate([
            'type' => $data['type']
        ],[
            'type' => $data['type'],
            'percent' => $data['percent']
        ]);

        return response()->json(['message' => 'Commission successfully set'],201);
    }

    public function fetchAllTransactions($query)
    {
        //
    }

    public function fetchTransactions($query)
    {
        $credit = '';
        $debit = '';

        $wallet_balance = $this->wallet->sum('balance');
        if(!empty($query))
        {
            $debit = $this->transactions->where('created_at','like','%'.$query.'%')
                ->where('type','debit')
                ->sum('amount');
            $credit = $this->transactions->where('created_at','like','%'.$query.'%')
                ->where('type','credit')
                ->sum('amount');

        } else {
            $debit = $this->transactions->where('type','debit')
                ->sum('amount');
            $credit = $this->transactions->where('type','credit')
                ->sum('amount');
//            $wallet_balance = $this->wallet->sum('balance');
        }

        $data = [
            'credit' => $credit,
            'debit' => $debit,
            'wallet_balance' => $wallet_balance,
            'vfd_balance' => 0
        ];
        return response()->json(['message' => 'Fetching successfully set','data' => $data],201);
    }

    private function balanceFromVFD()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->access_token
        ])->get($this->url.'client/create?wallet-credentials='.$this->wallet_id,[
            'payload' => 'string',
        ]);

        return $response;
    }
}
