<?php

use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth'])->group(function (){

//商品購入
Route::post('product/buy/{id}', 'SaleController@buy')->name('product.buy');

Route::get('product/sale/{id}', 'SaleController@sale')->name('product.sale');
});