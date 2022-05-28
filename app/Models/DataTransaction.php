<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class DataTransaction extends Model
{
    use UsesUuid;
    protected $fillable = [
        'transaction_id', 'status', 'phone', 'email', 'amount', 'amount_paid', 'commission', 'payment_method', 'payment_ref', 'platform', 'user_id', 'service_id', 'data_bundles_id'
    ];

    protected $with = [
        'service', 'bundle'
    ];

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_modified';

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function bundle() {
        return $this->belongsTo(DataBundle::class, 'data_bundles_id', 'id');
    }
}
