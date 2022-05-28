<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Api extends Model
{
    use UsesUuid;
    //
    protected $fillable = [
        'name', 'balance'
    ];
}
