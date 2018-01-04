<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property-read mixed $icon
 * @mixin \Eloquent
 */
class Currency extends Model
{

    const USD = 1;

    protected $fillable = [ 'symbol', 'name', 'slug' ];

    public function isUSD()
    {
        return $this->id === self::USD;
    }

    public function getIconAttribute()
    {
        return $this->isUSD() ? 'fa fa-dollar-sign' : "cc {$this->symbol}";
    }
}
