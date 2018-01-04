<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PriceHelper
{
    public static function getPrices(Collection $balances)
    {
        foreach ($balances as &$balance) {
            $price = self::fetch($balance->currency->slug);
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

    public static function fetch($currency = NULL)
    {
        $data = collect();

        try {
            $Guzzle = new Client();

            if (NULL === $currency) {
                $response = $Guzzle->get('https://api.coinmarketcap.com/v1/ticker/?limit=0');

                if ($response->getStatusCode() === 200) {
                    $data = collect(json_decode($response->getBody()->getContents()))->keyBy('symbol');

                    foreach ($data as $symbol => $price) {
                        Cache::put("price.{$symbol}", $price, 1);
                    }
                }
            } else {
                $response = $Guzzle->get("https://api.coinmarketcap.com/v1/ticker/{$currency}/");

                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody()->getContents());
                    Cache::put("price.{$data->symbol}", $data, 1);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return is_a($data, Collection::class) ? $data : $data[0];
    }

}