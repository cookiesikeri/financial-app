<?php

return [

    /*
    |--------------------------------------------------------------------------
    | VFD TEST CLIENT DETAILS
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to
    | get the details for a client used for testing
    |
    */

    'test_client_username' => env('VFD_CLIENT_USERNAME', null),
    'test_client_id' => env('VFD_CLIENT_ID', null),
    'test_saving_id' => env('VFD_SAVINGS_ID', null),
    'test_vfd_bvn' => env('VFD_BVN', null),

    /*
    |--------------------------------------------------------------------------
    | VFD API KEYS
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to
    | get the details for BVN validation
    |
    */

    'key' => env('VFD_KEY', null),
    'url' => env('VFD_URL', null),
    'wallet_id' => env('VFD_WALLET_ID', null),
    'hook_url' => env('VFD_HOOK_URL', null),

    /*
    |-----------------------------------------------------------------------------
    | LOANS
    |-----------------------------------------------------------------------------
    | default duration of a loan in days
    |
    */

    'loan_duration' => env('LOAN_DURATION', 10),
];
