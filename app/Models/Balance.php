<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Balance extends Model
{

    protected $casts = [
        'balance' => 'float',
        'price'   => 'float',
    ];

    protected $with = [ 'currency' ];

    protected $fillable = [ 'currency_id', 'balance', 'price' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'coin_id');
    }

    public static function latest()
    {
        return Balance::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->firstOrNew([]);
    }

    public function setSymbolAttribute($value)
    {
        $this->attributes['symbol'] = strtoupper($value);
    }
}
