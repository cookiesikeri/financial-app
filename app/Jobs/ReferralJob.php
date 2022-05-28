<?php

namespace App\Jobs;

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReferralJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $referred_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code, $referred_id)
    {
        $this->code = $code;
        $this->referred_id = $referred_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $referrer = ReferralCode::on('mysql::read')->where('referral_code', $this->code)->first();

        $referral = Referral::on('mysql::write')->create([
            'referrer_id'=>$referrer->user->id,
            'referred_id'=>$this->referred_id,
            'referral_code'=>$this->code,
        ]);

    }
}
