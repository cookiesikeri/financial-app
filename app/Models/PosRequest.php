<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PosRequest extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];

    public function pos_locations()
    {
        return $this->hasMany(PosLocation::class);
    }
}
