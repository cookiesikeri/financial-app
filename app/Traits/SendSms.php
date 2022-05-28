<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait SendSms
{
    public function sendSms($phone,$message)
    {
        $response = Http::post('https://www.bulksmsnigeria.com/api/v1/sms/create?api_token='.env('BULKSMS_TOKEN').'&to='.$phone.'&from=TRANSAVE&body='.$message.'&dnd=');

        return $response;
    }
}
