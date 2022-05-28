<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanKyc extends Model
{
    protected $table = 'loan_kycs';

    use HasFactory, UsesUuid;
    protected $guarded = [
        'id'
    ];

    public function loans() {
        return $this->hasMany(Loan::class, 'loan_kyc_id', 'id');
    }
    public function user() {
        return $this->belongsTo(User::class );
    }
    public function educationalqualification() {
        return $this->belongsTo(EducationalQualification::class, 'educational_qualification_id' );
    }
    public function residentialstatus() {
        return $this->belongsTo(ResidentialStatus::class, 'residential_status_id' );
    }
    public function employmentstatus() {
        return $this->belongsTo(EmploymentStatus::class, 'employment_status_id' );
    }
    public function monthlyincome() {
        return $this->belongsTo(MonthlyIncome::class, 'monthly_income_id' );
    }

}
