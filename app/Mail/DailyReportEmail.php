<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $totalUsers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($totalUsers)
    {
        $this->totalUsers = $totalUsers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $dateTime = Carbon::now('Africa/Tunis')->format('l jS F Y');
        return $this->subject("Transave Support Statistics Report System Support ($dateTime)")->markdown('emails.daily_report_email');
    }
}
