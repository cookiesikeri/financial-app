<?php

namespace App\Jobs;

use App\Models\Pos;
use App\Models\PosPurchase;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ManagesCommission;
use App\Traits\SendSms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PosPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,SendSms,ManagesCommission;

    public $wallet;

    const POS = [
        'packs' => [
            'price' => 15000
        ],
        'android' => [
            'price' => 50000
        ]
    ];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pos = Pos::on('mysql::read')->where('user_id',$this->wallet->user_id)
            ->where('is_paid',0)
            ->get();

        foreach ($pos as $p) {
            if($this->wallet->balance >= self::POS[$p->pos_type]['price']) {
                $amount = self::POS[$p->pos_type]['price'];
                $verifyInitialPayment = PosPurchase::on('mysql::read')
                    ->where('user_id',$this->wallet->user_id)
                    ->where('terminalId',$p->terminalId)->first();

                if(empty($verifyInitialPayment)) {
                    $wallet = Wallet::on('mysql::write')
                        ->where('user_id',$this->wallet->user_id)->first();

                    $wallet->update([
                        'balance' => (int) $wallet->balance - $amount
                    ]);

                    PosPurchase::on('mysql::write')->create([
                        'price' => $amount,
                        'user_id' => $this->wallet->user_id,
                        'pos_type' => 'packs',
                        'terminalId' => $p->terminalId
                    ]);

                    //Pos Notification SMS
                    $message = "You've been charge ".$amount."for POS assigned";
                    $pos = Pos::on('mysql::read')->where('id',$p->id)->first();
                    $pos->update([
                        'is_paid' => 1
                    ]);

                    $this->sendSms($wallet->user->phone,$message);

                    $this->secureCommission($amount);

                    WalletTransaction::on('mysql::write')->create([
                        'wallet_id' => $wallet->id,
                        'amount' => $amount,
                        'type' => 'Debit',
                        'bank_name' => 'TRANSAVE POS CORPORATE',
                        'reference' => "POS-REQUEST"
                    ]);
                }
            }
        }
    }
}
