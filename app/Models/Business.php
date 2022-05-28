<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Business extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];

    public function wallet(){

        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function staff(){
        return $this->hasMany(BusinessStaff::class);
    }

    public function roles(){
        return $this->hasMany(BusinessRoles::class);
    }

    public function beneficiaries(){
        return $this->hasMany(Beneficiaries::class);
    }
}
