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

Route::middleware(['middleware' => 'api'])->group(function(){
Route::get('/', 'Homecontroller@index')->name('root.login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//商品一覧画面
Route::get('/list', 'ProductController@showList')->name('product.list');
Route::get('/search','ProductController@searchProducts')->name('product.searchProducts');
// 商品情報のソート
Route::get('/list/sort/id', 'ProductController@sortId')->name('sort-id');

// 商品情報のソート
Route::get('/list/sort/product_name', 'ProductController@sortProduct_name')->name('sort-product_name');

// 商品情報のソート
Route::get('/list/sort/price', 'ProductController@sortPrice')->name('sort-price');

// 商品情報のソート
Route::get('/list/sort/stock', 'ProductController@sortStock')->name('sort-stock');

// 商品情報のソート
Route::get('/list/sort/company_name', 'ProductController@sortCompany_name')->name('sort-company_name');

//①ルーティング作成(登録画面表示・ブログ登録)
//②コントローラーの作成(登録画面の表示)
//③登録画面のBladeを表示(CSRF対策)
//④コントローラーの作成(ブログ登録)
//⑤バリデーション作成
//⑥エラー処理

// 商品登録画面
Route::get('/product/create','productController@showCreate')->name('product.create');
// 新規商品登録フォーム
Route::post('/product/store','productController@exeStore')->name('product.store');
//商品詳細画面
Route::get('/product/{id}', 'ProductController@showDetail')->name('product.detail');
//商品詳細編集画面
Route::get('/product/edit/{id}', 'ProductController@showEdit')->name('product.edit');
Route::post('/product/update', 'ProductController@exeUpdate')->name('product.update');
//商品削除画面
Route::post('/product/delete','ProductController@exeDelete')->name('product.delete');
});