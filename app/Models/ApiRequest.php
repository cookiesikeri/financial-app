<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ApiRequest extends Model
{
    use UsesUuid;
    // status = 0 for failed transactions and 1 for successful transactions
    protected $fillable = [
        'request', 'response', 'request_timestamp', 'response_timestamp', 'api_id', 'status', 'receiver', 'ref', 'response_hash'
    ];
}
