<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OTP expiry duration
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to
    | determine the number of minutes before an opt expires
    |
    */
    'validity' => env('OTP_VALIDITY', 10),
];
