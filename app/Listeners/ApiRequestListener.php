<?php

namespace App\Listeners;

use App\Events\ApiRequestEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApiRequestListener
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
     * @param  ApiRequestEvent  $event
     * @return void
     */
    public function handle(ApiRequestEvent $event)
    {
        $apiRequest = $event->apiRequest;
        \App\Models\ApiRequest::on('mysql::write')->create($apiRequest);
    }
}
