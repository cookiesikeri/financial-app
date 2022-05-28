<?php

namespace App\Console\Commands;

use App\Mail\TransactionMail;
use App\Models\RotationalSaving;
use App\Models\SavedCard;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreditRotationalSavingsAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rotational:credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credits the member of the Rotational Savings group whose turn it is to receive payment.';

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
        //Get All Rotational Savings Group.
        $membersTurn = '';
        $accounts = Saving::on('mysql::write')->where('type', 'Rotational')->get();
        foreach ($accounts as $account) {
            //Get the members of a particular group.
            $members = RotationalSaving::on('mysql::write')->where('account_id', $account->id)->get();
            foreach ($members as $member) {
                //Check who has paid and who has not
                if($account->next_turn == $member->turn){
                    $membersTurn = $member->member_id;
                }
                if($member->mid_payment != 1) {
                    //Debit those who have not paid
                    $user = User::on('mysql::write')->find($member->member_id);
                    $walletBalance = intval($user->wallet->balance);
                    $amountToSave = intval($account->amount);

                    //Check for sufficient funds in wallet
                    if($amountToSave <= $walletBalance){
                        //Sufficient funds, debit wallet
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
                            'status'=>'success',
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
                    }
                    elseif ($amountToSave > $walletBalance)
                    {
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
                    //Reset mid payment to zero.
                    RotationalSaving::on('mysql::write')->where([['account_id', $member->account_id], ['member_id', $member->member_id]])->update(['mmid_payment', 0]);
                }
            }

            //Check what the current turn is and compare which member matches it
            //Member with current turn gets credited.

            //Get the member whose turn it is and credit his wallet.
            $memberWallet = Wallet::on('mysql::write')->where('user_id', $membersTurn)->get()->first();
            $accountB = Saving::on('mysql::write')->where('id', $account->id)->get()->first();

            if($memberWallet && $accountB){
                $rotAccBalance = intval($accountB->balance);
                $memberBalance = intval($memberWallet->balance);
                $latestBalance = $rotAccBalance + $memberBalance;

                $lastTurn = $accountB->next_turn;
                $nextturn = intval($accountB->next_turn) + 1;

                $memberWallet->update(['balance'=>$latestBalance]);

                //Add to wallet transaction table
                WalletTransaction::on('mysql::write')->create([
                    'wallet_id'=> $memberWallet->id,
                    'type'=>'Credit',
                    'amount'=>$latestBalance,
                    'status'=>'success',
                ]);
                //Add to savings transaction table
                $sTransactionData = array(
                    'savingsId'=>$account->id,
                    'userId'=>$memberWallet->user_id,
                    'amount'=>$latestBalance,
                    'date_deposited'=>date('Y-m-d H:i:s'),
                    'type'=>'Debbit'
                );
                //$this->savingsTransaction->save($sTransactionData);
                SavingTransaction::on('mysql::write')->create($sTransactionData);

                //Balance of rotational savings goes back to zero.
                ////Current turn gets updated to next turn
                $accountB->update(['balance'=>0, 'last_turn'=>$lastTurn, 'next_turn'=>$nextturn]);

            }
        }

        //return 0;
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
