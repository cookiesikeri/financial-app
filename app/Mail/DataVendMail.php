<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DataVendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $dataTransaction;
    public $is_vendor = false;
    public function __construct(\App\Models\DataTransaction $dataTransaction)
    {
        /* $this->dataTransaction = $dataTransaction;
        $vendor = \App\Models\Vendor::where('user_id', $this->dataTransaction->user_id)->first();
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
        return $this->from('support@transave.com.ng', 'Transave Nigeria')->subject('Transave Data Subscription')->view('mails.data_notification');
    }
}
