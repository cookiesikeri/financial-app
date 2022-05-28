<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Loan extends Model
{
    use HasFactory, UsesUuid;

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanrepayments() {
        return $this->hasMany(LoanRepayment::class, 'loan_id');
    }

}
