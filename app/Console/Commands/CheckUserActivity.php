<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckUserActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if user has been active on the platform';

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
        $users = User::on('mysql::write')->all();
        foreach($users as $user){
            $lastActive = UserActivity::on('mysql::read')->where('user_id', $user->id)->sortByDesc('created_at')->take(1);
            //return Carbon::parse('09-04-2021')->addDays(20) <= Carbon::now();
            //Carbon::parse('09-04-2021')->diffInDays(Carbon::now()) >= 20;
            if(Carbon::parse($lastActive->created_at)->diffInDays(Carbon::now()) >= 20){
                $user->update([
                    'status'=>2
                ]);
            }
        }
        return 0;
    }
}
