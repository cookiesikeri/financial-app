<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class DataBundle extends Model
{
    use UsesUuid;
    protected $fillable = [
        'name', 'amount', 'service_id', 'code'
    ];

    protected $with = [
        'dataTransactions'
    ];

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_modified';

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function dataTransactions() {
        return $this->hasMany(DataTransaction::class, 'id', 'data_bundles_id');
    }
}
