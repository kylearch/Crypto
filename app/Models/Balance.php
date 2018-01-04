<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Balance
 *
 * @property-read \App\Models\Currency $currency
 * @property-write mixed               $amount
 * @property-read \App\Models\User     $user
 * @mixin \Eloquent
 */
class Balance extends Model
{

    protected $casts = [ 'amount' => 'float', ];

    protected $with = [ 'currency' ];

    protected $fillable = [ 'user_id', 'currency_id', 'amount' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getPricePaidAttribute()
    {
        return Transaction::where([ 'user_id' => $this->user_id ])
            ->where(function($query) {
                $query->where([ 'to_id' => $this->currency_id, 'type' => Transaction::TYPE_BUY ]);
                $query->orWhere(function($query) {
                    $query->where([ 'from_id' => $this->currency_id, 'type' => Transaction::TYPE_SELL ]);
                });
            })->get()->sum(function($transaction) {
                return $transaction->type === Transaction::TYPE_BUY ? $transaction->amount_from : -$transaction->amount_to;
            });
    }

    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = max(0, $amount);
    }

    public function adjustAmount(float $amount)
    {
        $this->amount += $amount;
        $this->save();
    }

}
