<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    use HasFactory, UsesUuid;
    protected $guarded = [
        'id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
