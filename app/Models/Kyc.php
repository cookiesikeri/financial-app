<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory, UsesUuid;
//    protected $guarded = [
//        'id'
//    ];

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'middle_name', 'address', 'id_card_number',
        'passport_url', 'home_address', 'proof_of_address_url', 'id_card_type_id', 'id_card_url',
        'next_of_kin', 'next_of_kin_contact', 'mother_maiden_name', 'guarantor', 'guarantor_contact',
        'country_of_residence_id', 'country_of_origin_id', 'state_id', 'lga_id', 'city', 'is_completed'
    ];

    public function user() {
        return $this->belongsTo(User::class );
    }
    public function state() {
        return $this->belongsTo(State::class );
    }
    public function lga() {
        return $this->belongsTo(LGA::class, 'lga_id' );
    }
    public function residence() {
        return $this->belongsTo(Country::class, 'country_of_residence_id', 'id');
    }
    public function origin() {
        return $this->belongsTo(Country::class, 'country_of_origin_id', 'id');
    }
    public function idcardtype() {
        return $this->belongsTo(IdCardType::class, 'id_card_type_id', 'id');
    }

    protected $hidden = [
        'is_completed',
    ];
}
