<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PowerTransaction extends Model
{
    use UsesUuid;
    // by default status is pending = 0, active = 1, fulfilled = 2, failed = 3 and re-try = 4
    protected $fillable = [
        'transaction_id', 'token', 'status', 'meter_num', 'amount', 'amount_paid', 'commission', 'phone', 'email', 'payment_method', 'payment_ref', 'platform', 'customer_name', 'units', 'service_id', 'user_id', 'access_token'
    ];

    protected $with = [
        'service'
    ];

    //CONST CREATED_AT = 'date_created';
    //CONST UPDATED_AT = 'date_modified';

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
