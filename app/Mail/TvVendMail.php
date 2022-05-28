<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TvVendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $tvTransaction;
    public $is_vendor = false;
    public function __construct(\App\Models\TVTransaction $tvTransaction)
    {
        /* $this->tvTransaction = $tvTransaction;
        $vendor = \App\Models\Vendor::where('user_id', $this->tvTransaction->user_id)->first();
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
        return $this->from('support@expresstopup.ng', 'Expresstopup Nigeria')->subject('Expresstopup Cable Tv Subscription')->view('mails.tv_notification');
    }
}
