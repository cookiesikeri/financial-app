<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PersonalWalletFunding extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $status;
    public $description;
    public $amount_credited;
    public $new_wallet_balance;
    public $payment_reference;
    public function __construct($amount_credited, $new_wallet_balance, $payment_reference, $status, $description)
    {
        $this->status               = $status;
        $this->description          = $description;
        $this->amount_credited      = $amount_credited;
        $this->new_wallet_balance   = $new_wallet_balance;
        $this->payment_reference    = $payment_reference;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@transave.com.ng', 'Transave Nigeria')->subject('Wallet Transaction')->view('mails.personal-wallet-transaction');
    }
}
