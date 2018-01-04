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

Auth::routes();

Route::get('/', function() {
    return Auth::check() ? redirect()->route('balances.index') : redirect()->route('login');
});

Route::group([ 'middleware' => 'auth' ], function() {
    Route::resource('balances', 'BalanceController');
    Route::resource('transactions', 'TransactionController');

    Route::get('profile', [ 'as' => 'user.edit', 'uses' => 'UserController@edit' ]);
    Route::post('profile', [ 'as' => 'user.update', 'uses' => 'UserController@update' ]);
});

if (env('APP_ENV') === 'local') {
    Route::any('sandbox', [ 'uses' => 'SandboxController@index' ]);
}

