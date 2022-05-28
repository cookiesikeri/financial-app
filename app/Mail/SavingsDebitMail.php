<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SavingsDebitMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $amount;
    public $transaction;
    public $wallet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        //$this->transaction = $transaction;
        //$this->wallet = $wallet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Savings Account Debit Transaction Alert ['.number_format($this->amount).']')
            ->markdown('emails.savings_debit_email');
    }
}
