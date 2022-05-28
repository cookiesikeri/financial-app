<?php

namespace App\Jobs;

use App\Mail\CreditEmail;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ManagesTransactions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Classes\BankRegistrationHook;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class TransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ManagesTransactions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $transfer;
    public function __construct($data)
    {
        $this->transfer = new BankRegistrationHook;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        return 12;
        $wallet_transaction = WalletTransaction::on('mysql::write')->create([
            'wallet_id' => '576rtfg',
            'amount' => '90000000',
            'type' => 'Debit',
            'sender_account_number' => 'bjnmbmnnb'
        ]);
//        $this->bankTransfer($this->data);
//        Mail::to('ayanwoye74@gmail.com')
//        ->send('Send');
    }

    public function bankTransfer($request)
    {
        try{
            $user_id = $request->input('user_id');
            $pin = $request->input('pin');
            $amount = $request->input('amount');
            $recipient_account = $request->input('recipient_account');
            // $sender_bvn = $request->input('sender_bvn') ? $request->input('sender_bvn') : '22222252234';
            $narration = $request->input('narration') ? $request->input('narration') : 'narration';
            $bank_code = $request->input('bank_code');
            $transfer_type = $bank_code == 999999 ? 'intra' : 'inter';

            $account_verification = $this->verifyUserByBVN($transfer_type,$recipient_account,$bank_code);
            if($account_verification['status'] == 00){
                $user = User::on('mysql::read')->where('id',$user_id)->first();
                $transaction_pin = $user ? $user->transaction_pin : 0;
                if(Hash::check(trim($pin),$transaction_pin)){
                    $verifyAccountBalance = Wallet::on('mysql::write')->where('user_id',$user_id)->first();
                    if($verifyAccountBalance->balance >= $amount){
                        $balance = $verifyAccountBalance->balance - $amount;
                        $succesfulTransfer = $this->makeTransaction($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code);
                        if($succesfulTransfer['status'] == 00){
                            $verifyAccountBalance->update([
                                'balance' => $balance
                            ]);
                            // Send Email
                            $wallet_transaction = WalletTransaction::on('mysql::write')->create([
                                'wallet_id' => $verifyAccountBalance->id,
                                'amount' => $amount,
                                'type' => 'Debit',
                                'sender_account_number' => $recipient_account
                            ]);
                            Mail::to($user->email)
                                ->send(new CreditEmail($user->name,$request->amount,$wallet_transaction,$verifyAccountBalance));
                            $message = 'Your account '. substr($recipient_account,0,3).'XXXX'.substr($recipient_account,-3)." Has Been Debited with NGN".number_format($request->amount).'.00 On '.date('d-m-Y H:i:s',strtotime($wallet_transaction->created_at)).'. Bal: '.number_format($user->wallet->balance);
                            $this->sendSms($user->phone,$message);
                            return response()->json(['message'=> 'Simulated Successful Response From Nibss','txnId' => $succesfulTransfer['data']['txnId']], 201);
                        } else {
                            return response()->json(['message'=> 'Failed Transaction','txnId' => $succesfulTransfer['data']['txnId']], 403);
                        }

                    } else {
                        return response()->json(['message'=>'Insufficient Balance.'], 420);
                    }
                }else {
                    return response()->json(['message'=>'Wrong pin, You\'ve 3 more attempt.'], 403);
                }

            }else {
                return response()->json([
                    'message' => $account_verification['message'],
                    'status' => 404,
                ]);
            }

        } catch(\Exception $e) {
            Http::post('https://hooks.slack.com/services/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',[
                'text' => $e->getMessage(),
                'username' => 'BankRegistrationHook Class - (BANK Transfer)',
                'icon_emoji' => ':ghost:',
                'channel' => env('SLACK_CHANNEL'),
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $account_verification
     * @param $amount
     * @param $recipient_account
     * @param $narration
     * @param $transfer_type
     * @param $bank_code
     * @return \Illuminate\Http\Client\Response
     */
    private function makeTransaction($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code)
    {
        return $this->processBankTransfer($account_verification,$amount,$recipient_account,$narration,$transfer_type,$bank_code);

    }

    /**
     * @param $transfer_type
     * @param $recipient_account
     * @param $bank_code
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    private function verifyUserByBVN($transfer_type,$recipient_account,$bank_code)
    {
        return $this->processUserByBVN($transfer_type,$recipient_account,$bank_code);
    }
}
