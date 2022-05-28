<?php

namespace App\Listeners;

use App\Events\NewPowerTransactionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPowerTransactionEventListener
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
     * @param  NewPowerTransaction  $event
     * @return void
     */
    public function handle(NewPowerTransactionEvent $event)
    {
        $powerData = $event->powerData;

        \App\Models\PowerTransaction::on('mysql::write')->create($powerData);
    }
}
