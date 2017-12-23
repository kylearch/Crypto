<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class PriceHelper
{
    public static function getPrices(Collection $balances)
    {
        $Guzzle   = new Client();
        $response = $Guzzle->get('https://api.coinmarketcap.com/v1/ticker/?limit=0');

        $results = collect(json_decode($response->getBody()->getContents()))->keyBy('symbol');
        foreach ($balances as &$balance) {
            $price = $results->pull(strtoupper($balance->coin->symbol));

            $balance->value         = $price->price_usd * $balance->balance;
            $balance->current_price = money_format('%.2n', $balance->value);
            $balance->market_price  = money_format('%.2n', $price->price_usd);
            $balance->gain          = $balance->value - $balance->price;
            $balance->gain_usd      = money_format('%.2n', $balance->gain);
            $balance->gain_percent  = round(($balance->value / $balance->price) * 100) . "%";

            $balance->color = $balance->gain > 0 ? 'success' : 'danger';
        }
    }

}