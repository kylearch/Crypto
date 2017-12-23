<?php

use App\Models\Coin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function(Blueprint $table) {
            $table->increments('id');
            $table->string('symbol', 16);
            $table->string('slug');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('balances', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('coin_id');
            $table->decimal('balance', 64, 30);
            $table->decimal('price', 10);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('cascade');
        });

        $coins = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/?limit=0'));

        foreach ($coins as $coin) {
            Coin::create([
                'symbol' => $coin->symbol,
                'slug'   => $coin->id,
                'name'   => $coin->name,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('balances');
        Schema::drop('coins');
    }
}
