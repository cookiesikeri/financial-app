<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class CardRequestVirtual extends Model
{
    use HasFactory, UsesUuid;

    protected $table ="card_request_virtuals";

    protected $fillable = ['name', 'currency', 'status', 'card_type', 'amount', 'address', 'user_id'];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
