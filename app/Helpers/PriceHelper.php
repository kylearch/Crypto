<?php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PriceHelper
{
    public static function getPrices(Collection $balances)
    {
        $prices = Cache::has('prices') ? Cache::get('prices') : self::fetch();

        foreach ($balances as &$balance) {
            $price = $prices->pull(strtoupper($balance->coin->symbol));

            $balance->value         = $price->price_usd * $balance->balance;
            $balance->current_price = money_format('%.2n', $balance->value);
            $balance->market_price  = money_format('%.2n', $price->price_usd);
            $balance->gain          = $balance->value - $balance->price;
            $balance->gain_usd      = money_format('%.2n', $balance->gain);
            $balance->gain_percent  = round(($balance->value - $balance->price) / $balance->price * 100);

            $balance->color = $balance->gain > 0 ? 'success' : 'danger';
        }

        unset($prices);
    }

    public static function fetch()
    {
        try {
            $Guzzle   = new Client();
            $response = $Guzzle->get('https://api.coinmarketcap.com/v1/ticker/?limit=0');

            if ($response->getStatusCode() === 200) {
                $data = collect(json_decode($response->getBody()->getContents()))->keyBy('symbol');

                Cache::forever('last_fetch', Carbon::now());
                Cache::put('prices', $data, 60);

                return $data;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return collect();
    }

}