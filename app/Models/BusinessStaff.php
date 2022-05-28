<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessStaff extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];


    public function business(){
        return $this->belongsTo(Business::class);
    }
}
