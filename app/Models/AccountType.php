<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    public function user() {
        $this->hasOne(User::class, 'account_type_id');
    }
}
