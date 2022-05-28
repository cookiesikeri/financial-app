<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as ModelsPermission;
use App\Traits\UsesUuid;

class Permission extends ModelsPermission
{
    use HasFactory, UsesUuid;
}
