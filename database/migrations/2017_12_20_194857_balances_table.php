<?php

use App\Models\Transaction;
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
        Schema::create('currencies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('symbol', 16);
            $table->string('slug');
            $table->string('name');
            $table->unsignedInteger('rank');
            $table->timestamps();
        });

        Schema::create('balances', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('currency_id');
            $table->decimal('amount', 64, 30);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');

            $table->unique([ 'user_id', 'currency_id' ]);
        });

        Schema::create('transactions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->unsignedTinyInteger('type')->default(Transaction::TYPE_BUY);
            $table->decimal('amount_from', 64, 30)->default(0);
            $table->decimal('amount_to', 64, 30)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
        Schema::drop('balances');
        Schema::drop('currencies');
    }
}
