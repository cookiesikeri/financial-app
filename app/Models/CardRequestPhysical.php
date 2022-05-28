<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class CardRequestPhysical extends Model
{
    use HasFactory, UsesUuid;

    protected $table ="card_request_physical";

    protected $fillable = ['name', 'phone_number', 'status', 'address', 'state', 'lga', 'user_id'];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
