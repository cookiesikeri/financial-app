<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTeller extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [];
}
