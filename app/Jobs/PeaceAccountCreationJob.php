<?php

namespace App\Jobs;

use App\Classes\PeaceAPI;
use App\Models\AccountNumber;
use App\Models\Kyc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PeaceAccountCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $user;
    protected $peace;

    public function __construct($user)
    {
        $this->user = $user;
        $this->peace = new PeaceAPI();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Step one
        // Fetch KYC details of current logged in user
        // if he/she has updated kycs then fetch details to create the account
        // Log the account on the table

        if(!empty($this->user) && !empty($this->user->bvn) && $this->user->account_type_id >= 2) {

            $kyc_Verification = Kyc::where('user_id',$this->user->id)->first();
            $account_number = AccountNumber::where('wallet_id',$this->user->wallet->id)
                ->where('account_name','Peace Micro Finance Bank')->first();

            if(!empty($kyc_Verification) && empty($account_number)){
                $this->peace->clientSavingsAccount($this->user, $kyc_Verification);
            }
        }
    }
}
