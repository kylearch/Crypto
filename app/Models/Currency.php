<?php

namespace App\Models;

use App\Helpers\PriceHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    protected $appends = [ 'price' ];

    public function isUSD()
    {
        return $this->id === self::USD;
    }

    public function getIconAttribute()
    {
        return $this->isUSD() ? 'fa fa-dollar-sign' : "cc {$this->symbol}";
    }

    public function getPriceAttribute()
    {
        $key = "price.{$this->symbol}";

        return Cache::has($key) ? Cache::get($key) : PriceHelper::fetch($this->slug);
    }
}
