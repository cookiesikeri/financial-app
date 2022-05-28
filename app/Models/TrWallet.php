<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrWallet extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [
        'id'
    ];

    public function transaction() {
        return $this->belongsTo(TrWallet::class);
    }

    public function wallet() {
        return $this->hasOne(Wallet::class, 'receiver_wallet_id');
    }
}
