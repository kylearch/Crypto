<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

// Route::get('/', [ 'as' => 'index', 'uses' => 'TradeController@index' ]);

Route::get('/', function() {
    return redirect()->route('login');
});

Route::group([ 'middleware' => 'auth' ], function() {
    Route::resource('balances', 'BalanceController');
    Route::resource('transactions', 'TransactionController');

    Route::get('profile', [ 'as' => 'user.edit', 'uses' => 'UserController@edit' ]);
    Route::post('profile', [ 'as' => 'user.update', 'uses' => 'UserController@update' ]);
    // Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'TradeController@dashboard']);
    // Route::match(['get', 'post'], 'begin', ['as' => 'begin', 'uses' => 'TradeController@begin']);
});
Auth::routes();

if (env('APP_ENV') === 'local') {
    Route::any('sandbox', ['uses' => 'SandboxController@index']);
}

// Route::get('/home', 'HomeController@index')->name('home');
