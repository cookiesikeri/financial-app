<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class InviteeUser extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded =[];
}
