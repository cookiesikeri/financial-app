<?php

namespace App\Console\Commands;

use App\Mail\TransactionMail;
use App\Models\GroupSaving;
use App\Models\SavedCard;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreditGroupSavingsAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credits group savings account.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accounts = Saving::on('mysql::write')->where('type', 'Group')->get();
        foreach ($accounts as $account){
            $nextSave = '';
            if(strtoupper($account->cycle) == 'DAILY'){
                $nextSave = '1 day';
            }elseif (strtoupper($account->cycle) == 'WEEKLY'){
                $nextSave = '1 week';
            }elseif (strtoupper($account->cycle) == 'MONTHLY'){
                $nextSave = '1 month';
            }

            //Check if the next payment is due today
            $nextDate = $account->next_save;
            $today = date('Y-m-d');

            $nextTime = strtotime($nextDate);
            $todaysTime = strtotime($today);

            if($nextTime == $todaysTime) {
                $members = GroupSaving::on('mysql::write')->where('account_id', $account->id)->get();

                foreach ($members as $member) {
                    if($member->mid_payment != 1){
                        $user = User::on('mysql::write')->find($member->member_id);
                        $walletBalance = intval($user->wallet->balance);
                        $amountToSave = intval($account->amount);

                        if($amountToSave <= $walletBalance){
                            //Yes, Debit wallet
                            $depositAmount = intval($account->balance) + $amountToSave;
                            $newBalance = $walletBalance - $amountToSave;

                            //Update Savings Account with new balance
                            $account->update([
                                'balance' => $depositAmount
                            ]);

                            //Update wallet balance
                            $user->wallet()->update(['balance'=>$newBalance]);
                            //Add to wallet transaction table
                            WalletTransaction::on('mysql::write')->create([
                                'wallet_id'=> $user->wallet->id,
                                'type'=>'Debit',
                                'amount'=>$account->amount,
                                'status'=>'success'
                            ]);
                            //Add to savings transaction table
                            $sTransactionData = array(
                                'savingsId'=>$account->id,
                                'userId'=>$account->userId,
                                'amount'=>$account->amount,
                                'date_deposited'=>date('Y-m-d H:i:s'),
                                'type'=>'Deposit'
                            );
                            //$this->savingsTransaction->save($sTransactionData);
                            SavingTransaction::on('mysql::write')->create($sTransactionData);
                            Mail::to($user->email)->send(new TransactionMail($user->name,$amountToSave));

                        }elseif ($amountToSave > $walletBalance ) {
                            //Get Card details
                            //$card = SavedCard::where([['user_id', $member->member_id],['signature', $member->card_signature]])->get()->first();
                            $amnt = intval($account->amount) * 100;
                            $cards = $user->cards;
                            if($cards != null || !empty($cards)){
                                foreach($cards as $card){
                                    $verified = $this->debitCard($card, $amnt);
                                    if($verified['status'] == 1){
                                        $depositAmount = intval($account->balance) + (intval($verified['amount'])/100);
                                        $memBal = intval($member->balance) + (intval($verified['amount'])/100);
                                        //Update Savings Account with new balance
                                        $account->update([
                                            'balance' => $depositAmount,
                                        ]);

                                        $member->update(['balance'=>$memBal]);
                                        //Record transaction in Savings Transaction
                                        $sTransactionData = array(
                                            'savingsId'=>$account->id,
                                            'userId'=>$member->member_id,
                                            'amount'=>intval($verified['amount'])/100,
                                            'date_deposited'=>date('Y-m-d H:i:s'),
                                            'type'=>'Deposit'
                                        );
                                        //$this->savingsTransaction->save($sTransactionData);
                                        SavingTransaction::on('mysql::write')->create($sTransactionData);
                                        Mail::to($user->email)->send(new TransactionMail($user->name,intval($verified['amount'])/100));
                                        break;
                                    }
                                }
                            }
                        }
                    }elseif ($member->mid_payment == 1){
                        //Reset members mid payment to zero.
                        $member->update(['mmid_payment', 0]);
                    }
                }

                $account->update(['next_save'=>date('Y-m-d', strtotime($nextDate.$nextSave))]);
            }
        }
    }

    public function debitCard($card, $amnt){
        //Send request to paystack to debit the card.
        $key = env('PAYSTACK_SECRET_KEY');
        $url = "https://api.paystack.co/transaction/charge_authorization";
        
        $fields = [
            'authorization_code' => $card->auth_code,
            'email' => $card->user_email,
            'amount' => $amnt
        ];

        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".$key,
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        //Check if transaction was successfull
        if(curl_errno($ch)){
            //Log::error(curl_error($ch));
            $verified = 1;
            return array('status' => -1);
        }else{
            $res = json_decode($result, true);
            if($res['data'] && $res['data']['status'] == 'success'){
                $verified = 1;
                $amount = $res["data"]["amount"];
                return array('status' => $verified, 'amount' => $amount);
            }
        }
    }
}
