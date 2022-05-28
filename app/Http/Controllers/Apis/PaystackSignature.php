<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class PaystackSignature implements SignatureValidator{
    public function isValid(Request $request, WebhookConfig $config): bool{}
}