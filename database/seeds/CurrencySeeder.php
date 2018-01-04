<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'symbol' => 'USD',
            'slug'   => 'usd',
            'name'   => 'Dollar',
            'rank'   => 0,
        ]);

        $coins = collect(json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/?limit=0')));
        foreach ($coins->sortBy('rank') as $coin) {
            Currency::create([
                'symbol' => $coin->symbol,
                'slug'   => $coin->id,
                'name'   => $coin->name,
                'rank'   => $coin->rank,
            ]);
        }
    }
}
