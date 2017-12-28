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

Route::get('/', function() { return redirect()->route('login'); });

Route::group([ 'middleware' => 'auth' ], function() {
    Route::resource('transactions', 'TransactionController');
});

Auth::routes();

