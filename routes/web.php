<?php

use App\Http\Controllers\GetCurrencyController;
use App\Http\Controllers\CurrencyController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/getcurrency', GetCurrencyController::class);

Route::get('/', [CurrencyController::class, 'index'])->name('currency.index');
Route::get('/currencies/data', [CurrencyController::class, 'getCurrencies'])->name('currency.data');