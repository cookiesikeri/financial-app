<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $guarded = [
        "id"
    ];

    public function kycorigin() {
        return $this->hasOne(Kyc::class, 'country_of_origin_id', 'id');
    }
    public function kycresidence() {
        return $this->hasOne(Kyc::class, 'country_of_residence_id', 'id');
    }
}
