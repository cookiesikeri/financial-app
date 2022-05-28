<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PosLocation extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];


    public function pos_request()
    {
        return $this->belongsTo(PosRequest::class);
    }
}
