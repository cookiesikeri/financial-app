<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ContactMessage extends Model
{
    use UsesUuid;
    protected $table ="contact_messages";

    protected $fillable = ['subject', 'message', 'name', 'email', 'phone_number'];
}
