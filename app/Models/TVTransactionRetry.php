<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class TVTransactionRetry extends Model
{
    use UsesUuid;
    protected $table = "tv_transaction_retrials";
    protected $fillable = [
        'transaction_id', 'token'
    ];
}
