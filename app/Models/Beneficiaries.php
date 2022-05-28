<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Beneficiaries extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }
}
