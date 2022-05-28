<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Service extends Model
{
    use UsesUuid;
    protected $table = 'service';
    protected $fillable = [
        'name', 'status', 'service_charge', 'commission', 'service_type_id', 'api_id', 'minimum_value', 'maximum_value'
    ];

    public function dataBundles() {
        return $this->hasMany(DataBundle::class);
    }

    public function airtimeTransactions() {
        return $this->hasMany(AirtimeTransaction::class);
    }

    public function dataTransactions() {
        return $this->hasMany(DataTransaction::class);
    }

    public function powerTransactions() {
        return $this->hasMany(PowerTransaction::class);
    }
}
