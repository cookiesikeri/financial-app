<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Wallet extends Model implements Searchable
{
    use UsesUuid;

    protected $fillable = [
        'user_id',
        'credit',
        'balance',
        'account_number'
    ];

    public function getSearchResult(): SearchResult
    {     
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->wallet_id,
        );
    }

    /**
     * Sets relationship between the Wallets and Users.
     * A wallet belongs to only one User.
     */
    public function user(){

        return $this->belongsTo('App\Models\User');
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function account_numbers(){
        return $this->hasMany(AccountNumber::class);
    }

    public function trwallets() {
        return $this->hasMany(TrWallet::class, 'receiver_wallet_id');
    }


    /**
     * Sets relationship between the Wallets and Wallet Transactions.
     * A wallet can have multiple wallet transactions.
     */
    public function walletTransaction(){

        return $this->hasMany('App\Models\WalletTransaction');
    }

}
