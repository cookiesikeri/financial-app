<?php

namespace App\Jobs;

use App\Mail\CreditEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CreditEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email,$name,$amount,$transaction, $updateWalletBalance;

    public function __construct($email,$name,$amount,$transaction,$updateWalletBalance)
    {
        $this->email = $email;
        $this->name = $name;
        $this->amount = $amount;
        $this->transaction = $transaction;
        $this->updateWalletBalance = $updateWalletBalance;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)
            ->send(new CreditEmail($this->name,$this->amount,$this->transaction,$this->updateWalletBalance));;
    }
}
