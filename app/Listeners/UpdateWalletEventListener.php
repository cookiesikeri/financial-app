<?php

namespace App\Listeners;

use App\Events\UpdateWalletEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateWalletEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdateWalletEvent  $event
     * @return void
     */
    public function handle(UpdateWalletEvent $event)
    {
        $walletID = $event->walletID;
        $balance = $event->balance;

        \App\Models\Api::on('mysql::write')->where('id', $walletID)->update([
            'balance'   =>  $balance
        ]);
    }
}
