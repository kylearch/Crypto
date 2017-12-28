<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_BUY  = 0;
    const TYPE_SELL = 1;

    protected $casts = [
        'type'   => 'integer',
        'amount' => 'float',
        'price'  => 'float',
    ];

    protected $with = [ 'currency' ];

    protected $fillable = [ 'currency_id', 'type', 'amount', 'price' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // public function getAmountAttribute()
    // {
    //     return $this->type === self::TYPE_BUY ? $this->attributes['amount'] : -1 * $this->attribues['amount'];
    // }
}
