<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ServiceType extends Model
{
    use UsesUuid;
    protected $table = 'service_type';

    protected $fillable = [
        'name'
    ];
}
