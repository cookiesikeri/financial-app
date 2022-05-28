<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountRequest extends Model
{
    use HasFactory, UsesUuid;
    public $guarded = [
        'id'
    ];

    public function accounttype() {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
