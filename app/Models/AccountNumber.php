<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class AccountNumber extends Model implements Searchable
{
    use HasFactory, UsesUuid;

    protected $guarded = [];

    public function getSearchResult(): SearchResult
    {    
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->account_number
        );
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
