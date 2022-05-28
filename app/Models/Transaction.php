<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, UsesUuid;
    public $guarded = [
        'id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function trbank() {
        return $this->hasOne(TrBank::class, 'transaction_id');
    }

    public function trwallet() {
        return $this->hasOne(TrWallet::class, 'transaction_id');
    }
}
