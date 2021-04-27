<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/exchange-rates', [App\Http\Controllers\ExchangeRatesController::class, 'getExchangeRatesData'])->name('exchange-rates');

Route::post('/get-available-currencies', [App\Http\Controllers\ExchangeRatesController::class, 'getCurrencies'])->name('get-available-currencies');


Auth::routes();

Route::group(['middleware' => ['auth']], function () { 
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
});




