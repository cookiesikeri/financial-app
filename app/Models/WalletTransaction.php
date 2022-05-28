<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class WalletTransaction extends Model
{
    use UsesUuid;

    protected $guarded = [];
    /**
     * Sets relationship between the Wallet Transactions and Wallet.
     * A wallet transaction is associated with a particular user's wallet.
     */
    public function wallet(){

        return $this->belongsTo('App\Models\Wallet');
    }
}
