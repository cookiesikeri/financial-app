<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class FAQ extends Model

{
    use UsesUuid;
    protected $table = "faqs";

    protected $fillable = ['title', 'body', 'image'];
}
