<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class TVBundle extends Model
{
    use UsesUuid;
    protected $table = "tv_bundles";
    protected $fillable = [
        // this is why you don't code when you're loosing it. Can't believe I actually wrote this next line :-(
        // 'name'  =>  'amount'
        'name', 'amount', 'code', 'available', 'allowance', 'service_id'
    ];
    CONST CREATED_AT = 'date_created';
    CONST UPDATED_AT = 'date_modified';

    public function tvTransactions() {
        return $this->hasMany(TVTransaction::class, 'id', 'tv_bundles_id');
    }
}
