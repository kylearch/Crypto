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


Route::group(['middleware' => 'auth'], function() {
   // Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'TradeController@dashboard']);
   // Route::match(['get', 'post'], 'begin', ['as' => 'begin', 'uses' => 'TradeController@begin']);
   Route::resource('balances', 'BalanceController');
});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
