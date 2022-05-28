<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanBalance extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [
        'id'
    ];

    public function user(){

        return $this->belongsTo('App\Models\User');
    }

    public function loanTransaction(){

        return $this->hasMany('App\Models\LoanTransaction');
    }
}
