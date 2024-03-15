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

// use App\Http\Livewire\Products\StockPurchase\Index as StockPurchaseIndex;
use App\Http\Livewire\Products\StockPurchase\Create as StockPurchaseCreate;
use App\Http\Livewire\Products\StockPurchase\Update as StockPurchaseUpdate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/products/create', StockPurchaseCreate::class)->name('stock-purchase.create');

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth', 'admin'],
], function () {
    // Route::get('/products', StockPurchaseIndex::class)->name('stock-purchase.index');
    Route::get('/products/create', StockPurchaseCreate::class)->name('stock-purchase.create');
    Route::get('/products/{product}/edit', StockPurchaseUpdate::class)->name('stock-purchase.update');
});
