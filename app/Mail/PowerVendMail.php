<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PowerVendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $powerTransaction;
    public $is_vendor = false;
    public function __construct(\App\Models\PowerTransaction $powerTransaction)
    {
        /* $this->powerTransaction = $powerTransaction;
        $vendor = \App\Models\Vendor::where('user_id', $this->powerTransaction->user_id)->first();
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
        return $this->from('support@transave.com.ng', 'Transave Nigeria')->subject('Transave Power Token')->view('mails.power_notification');
    }
}
