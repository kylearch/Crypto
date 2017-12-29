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

            $zeros  = strspn($price->price_usd, '0', strpos($price->price_usd, '.') + 1);
            $zeros  += ($zeros >= 2) ? 2 : 0;
            $zeros  = max(2, $zeros);
            $format = "%.{$zeros}n";

            $balance->value         = $price->price_usd * $balance->balance;
            $balance->current_price = money_format('%.2n', $balance->value);
            $balance->market_price  = money_format($format, $price->price_usd);
            $balance->gain          = $balance->value - $balance->price;
            $balance->gain_usd      = money_format('%.2n', $balance->gain);
            $balance->gain_percent  = round(($balance->value - $balance->price) / $balance->price * 100);
            $balance->change_hour   = $price->percent_change_1h;
            $balance->change_day    = $price->percent_change_24h;
            $balance->change_week   = $price->percent_change_7d;

            $balance->color = $balance->gain > 0 ? 'success' : 'danger';
        }

        unset($prices);
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