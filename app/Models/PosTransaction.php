<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'terminalId',
        'rrn',
        'pan',
        'stan',
        'amount',
        'cardExpiry',
        'merchantId',
        'reference',
        'statusDescription',
        'transactionDate',
        'transactionType',
        'wallet_id',
        'reversal',
    ];
}
