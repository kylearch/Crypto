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

    protected $with = [ 'coin' ];

    protected $fillable = [ 'coin_id', 'balance', 'price' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coin()
    {
        return $this->belongsTo(Currency::class);
    }

}
