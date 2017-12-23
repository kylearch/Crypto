<?php

namespace App\Http\Controllers;

use App\Helpers\ShapeShift;
use App\Models\Balance;
use App\Models\Coin;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \RuntimeException
     */
    public function dashboard(Request $request)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');

        $current = Balance::latest();

        if ( ! $current->exists) {
            return redirect()->route('begin');
        }

        $data   = (new ShapeShift())->getInfo();
        $prices = $this->getPriceData();

        $current_value = money_format('%.2n', (float) $prices->get($current->coin->symbol)->price_usd * $current->balance);

        $results = $this->formatData($data, $prices, $current);
        $results = $this->filterByCurrentGains($results, $current);

        return view('index', compact('current', 'current_value', 'results'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function begin(Request $request)
    {
        $coins = Coin::orderBy('name', 'asc')->get();

        if ($request->isMethod('post')) {
            Auth::user()->balances()->create($request->only([ 'coin_id', 'balance', 'price' ]));

            return redirect()->route('dashboard');
        }

        return view('begin', compact('coins'));
    }

    /**
     * @return Collection
     * @throws \RuntimeException
     */
    private function getPriceData(): Collection
    {
        $Guzzle   = new Client();
        $response = $Guzzle->get('https://api.coinmarketcap.com/v1/ticker/?limit=0');

        return collect(json_decode($response->getBody()->getContents()))->keyBy('symbol');
    }

    private function formatData(Collection $data, Collection $prices, $current)
    {
        $results = collect();
        $price   = $prices->get($current->coin->symbol);

        $data->filter(function($value) use ($current) {
            return starts_with(strtoupper($value->pair), $current->coin->symbol);
        })->each(function($value) use ($price, $prices, $results, $current) {
            $symbol = strtoupper(str_after($value->pair, '_'));
            $price  = $prices->pull($symbol);
            $coins  = $current->balance * (float) $value->rate;
            $net    = $coins - (float) $value->minerFee;
            $usd    = $net * $price->price_usd;

            $results->put($symbol, [
                'name'      => $price->name,
                'symbol'    => $price->symbol,
                'gain'      => round($usd - $current->price, 2),
                'value'     => $usd,
                'coins'     => $net,
                'diff'      => $price->percent_change_1h - $price->percent_change_1h,
                'price'     => (float) $price->price_usd,
                'rate'      => (float) $value->rate,
                'min'       => $value->min,
                'fee'       => $value->minerFee,
                'can_trade' => $net > $value->min,
            ]);
        });

        return $results;
    }

    private function filterByCurrentGains(Collection $results, $current)
    {
        return $results->sortByDesc('gain')->filter(function($res) {
            return $res['can_trade'];
        })->groupBy(function($res) use ($current) {
            return $res['gain'] > 0 && $res['value'] > $current->price ? 0 : 1;
        })->all();
    }

    private function filterByRelativeGains(Collection $results, $current)
    {
        return $results;
    }
}
