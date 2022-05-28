<?php

namespace App\Console;

use App\Console\Commands\CheckUserActivity;
use App\Console\Commands\CreditAgentSavingsAccount;
use App\Console\Commands\CreditGroupSavingsAccount;
use App\Console\Commands\CreditRotationalSavingsAccount;
use App\Console\Commands\CreditSavingsAccounts;
use App\Console\Commands\DailyReport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreditSavingsAccounts::class,
        CreditGroupSavingsAccount::class,
        CreditRotationalSavingsAccount::class,
        CreditAgentSavingsAccount::class,
        DailyReport::class,
        CheckUserActivity::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('personal:credit')->dailyAt('23:59');
        $schedule->command('group:credit')->dailyAt('23:59');
        $schedule->command('rotational:credit')->dailyAt('23:59');
        $schedule->command('agent:credit')->dailyAt('23:59');
        $schedule->command('transave:report')->dailyAt('23:59');
        $schedule->command('user:activity')->dailyAt('23:59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
