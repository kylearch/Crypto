<?php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PriceHelper
{
    public static function getPrices(Collection $balances)
    {
        $prices = Cache::has('prices') ? Cache::get('prices') : self::fetch();

        foreach ($balances as &$balance) {
            $price = $prices->pull(strtoupper($balance->currency->symbol));
            $value = ($price->price_usd * $balance->amount);
            $paid  = $balance->price_paid;

            $balance->value   = NumberHelper::format($value, TRUE);
            $balance->price   = NumberHelper::format($price->price_usd);
            $balance->gain    = $paid !== 0 ? $value - $paid : 0;
            $balance->percent = $paid !== 0 ? round(($balance->value - $balance->price_paid) / $balance->price_paid * 100) : 0;

            $balance->change_hour = $price->percent_change_1h;
            $balance->change_day  = $price->percent_change_24h;
            $balance->change_week = $price->percent_change_7d;

            $balance->color = 'dark';
            if ($balance->gain !== 0) {
                $balance->color = $balance->gain > 0 ? 'success' : 'danger';
            }
        }
        unset($prices, $balance);
    }

    public static function fetch()
    {
        $data = collect();

        try {
            $Guzzle   = new Client();
            $response = $Guzzle->get('https://api.coinmarketcap.com/v1/ticker/?limit=0');

            if ($response->getStatusCode() === 200) {
                $data = collect(json_decode($response->getBody()->getContents()))->keyBy('symbol');

                Cache::put('prices', $data, 1);
                Cache::put('last_fetch', Carbon::now(), 1);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $data;
    }

}