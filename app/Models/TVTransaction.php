<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class TVTransaction extends Model
{
    use UsesUuid;
    // by default status is pending = 0, active = 1, fulfilled = 2, failed = 3 and re-try = 4
    protected $table = "tv_transactions";
    protected $fillable = [
        'transaction_id', 'status', 'smartcard_num', 'amount', 'amount_paid', 'commission', 'phone', 'email', 'payment_method', 'payment_ref', 'platform', 'customer_name', 'bundle_name', 'service_id', 'user_id', 'tv_bundles_id', 'access_token', 'transaction_trials'
    ];
    protected $with = [
        'bundle'
    ];

    CONST CREATED_AT = 'date_created';
    CONST UPDATED_AT = 'date_modified';

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function bundle() {
        return $this->belongsTo(TVBundle::class, 'tv_bundles_id', 'id');
    }
}
