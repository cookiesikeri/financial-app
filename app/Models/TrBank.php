<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrBank extends Model
{
    use HasFactory, UsesUuid;
    protected $guarded = [
        'id'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
