<?php


namespace App\Traits;


use App\Enums\ActivityType;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\TrBank;
use App\Models\TrWallet;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait ManagesTransactions
{
    public function processBankTransfer($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('VFD_KEY')
        ])->post(env('VFD_URL').'transfer?wallet-credentials='.env('VFD_WALLET_ID'),[
            "fromSavingsId" => env('VFD_SAVINGS_ID'),
            "amount" => $amount,
            "toAccount" => $recipient_account,
            "fromBvn" => env('VFD_BVN'),
            "signature" => Hash('sha512',env('VFD_ACCOUNT_NUMBER').$recipient_account),
            "fromAccount" => env('VFD_ACCOUNT_NUMBER'),
            "toBvn" => $account_verification['data']['bvn'],
            "remark" => $narration,
            "fromClientId" => env('VFD_CLIENT_ID'),
            "fromClient" => env('VFD_CLIENT_USERNAME'),
            "toKyc" => "99",
            "reference" => "transave-".uniqid(),
            "toClientId" => "",
            "toClient" => $account_verification['data']['name'],
            "toSession" => $account_verification['data']['account']['id'],
            "transferType" => $transfer_type,
            "toBank" => $bank_code,
            "toSavingsId" => ""
        ]);
        return $response;
    }

    public function processUserByBVN($transfer_type, $recipient_account, $bank_code)
    {
        try{
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.env('VFD_KEY')
            ])->get(env('VFD_URL').'transfer/recipient?transfer_type='.$transfer_type.'&accountNo='.$recipient_account.'&bank='.$bank_code.'&wallet-credentials='.env('VFD_WALLET_ID'));
            return $response;
        } catch(\Exception $e) {
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook Class - (VERIFY USER ACCOUNT)',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    public function totalSumOfDailyWithdrawals()
    {
        return Transaction::on('mysql::read')->where('user_id', auth()->id())
            ->where('type', TransactionType::DEBIT)->whereDate('created_at', Carbon::today())->sum('amount');
    }

    public function isWithdrawalValid($amount)
    {
        $intendingAmount = floatval($this->totalSumOfDailyWithdrawals()) + floatval($amount);
        if($intendingAmount > auth()->user()->withdrawal_limit) {
            return false;
        }
        return true;
    }

    public function createTransaction($amount, $activity, $type)
    {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'activity' => $activity,
            'type' => $type
        ]);

        return $transaction;
    }

    public function createBankTransaction(array $data, $transaction)
    {
        return TrBank::create([
            'transaction_id' => $transaction->id,
            'reference' => $data['reference']?? '',
            'bank' => $data['bank'],
            'receiver_account_number' => $data['account_number']?? '',
            'receiver_name' => $data['account_name']?? '',
            'description' => $data['description']?? '',
        ]);
    }

    public function createWalletTransaction(array $data, $transaction)
    {
        return TrWallet::create([
            'transaction_id' => $transaction->id,
            'receiver_wallet_id' => $data['receiver_wallet_id'],
            'reference' => $data['reference']?? '',
            'description' => $data['description']?? '',
        ]);
    }
}
