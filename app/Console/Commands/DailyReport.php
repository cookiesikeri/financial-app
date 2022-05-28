<?php

namespace App\Console\Commands;

use App\Mail\DailyReportEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transave:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily report on various Transave components.';

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
        $totalUsers = User::count();
        $to = explode(',', env('ADMIN_EMAILS'));
        Mail::to($to)->send(new DailyReportEmail($totalUsers));
        //return 0;
    }
}
