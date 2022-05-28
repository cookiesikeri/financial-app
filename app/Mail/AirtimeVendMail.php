<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AirtimeVendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $airtimeTransaction;
    public $is_vendor = false;
    public function __construct(\App\Models\AirtimeTransaction $airtimeTransaction)
    {
        /* $this->airtimeTransaction = $airtimeTransaction;
        $vendor = \App\Models\Vendor::where('user_id', $this->airtimeTransaction->user_id)->first();
        if (!empty($vendor)) {
            $this->is_vendor = true;
        } */
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@transave.com.ng', 'Transave Nigeria')->subject('Transave Airtime Purchase')->view('mails.airtime_notification');
    }
}
