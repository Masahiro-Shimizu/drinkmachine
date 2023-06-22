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

Route::middleware(['middleware' => 'api'])->group(function (){

//商品購入
Route::post('products/purchase', 'SaleController@purchase')->name('products.purchase');

Route::get('products/purchase', 'SaleController@sales')->name('products.sales');

});