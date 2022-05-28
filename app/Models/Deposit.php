<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Deposit extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['amount'];
}
