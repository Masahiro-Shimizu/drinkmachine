<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    //テーブル名
    protected $table = 'products';

    //可変項目
    protected $fillable = 
    [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function productList()
    {
       $products = DB::table('products')
       ->join('companies','products.company_id','=','companies.id')
       ->select(
        'products.id',
        'products.img_path',
        'products.product_name',
        'products.price',
        'products.stock',
        'products.comment',
        'companies.company_name',
       )
       ->orderBy('products.id','asc')
       ->get();

       return $products;
    }

    /**
     * キーワード検索
     * ＠param $param
     * @return $products
     */

    public function searchKeyword($param){
        $products = DB::table('products')
        ->join('companies','products.company_id','=','companies.id')
        ->select(
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'companies.company_name',
        )
        ->wherw('products.product_name','LIKE','%'.$param.'%')
        ->orderBy('products.id','asc')
        ->get();

        return $products;
    }

    /**
     * メーカー名選択検索
     * @param $param
     * @return $products
     */
    public function searchCompanyName($param){
        $products = DB::table('products')
        ->join('companies','products.company_id','=','companies.id')
        ->select(
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'companies.company_name',
        )
        ->where('products.company_id', $param)
        ->orderBy('products.id', 'asc')
        ->get();
        return $products;
    }

    /**
     * 商品詳細データ
     * 
     * @param $id
     * @return $product
     */

     public function productDetail($id) {
        $product = DB::table('products')
        ->join('companies', 'products.company_id','=','companies.id')
        ->select(
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'products.company_id',
            'companies.company_name',
        )
        ->where('products.id', $id)
        ->first();
        return $product;
    }

    /**
     * 商品登録
     * @param $param
     * 
     */
    public function createProduct($param) {
        DB::table('products')->insert([
            'company_id' => $param['company_id'],
            'product_name' => $param['product_name'],
            'price' => $param['price'],
            'stock' => $param['stock'],
            'comment' => $param['comment'],
            'img_path' => $param['img_path']
        ]);
    }

    /**
     * 商品詳細編集
     * @param $param
     * @return 
     */
    public function updateProduct($param) {
        DB::table('products')
        ->where('id',$param['id'])
        ->update([
            'company_id' => $param['company_id'],
            'product_name' => $param['product_name'],
            'price' => $param['price'],
            'stock' => $param['stock'],
            'comment' => $param['comment'],
            'img_path' => $param['img_path']
        ]);
    }

    /**
     * 商品情報削除
     * @param $id
     */
    public function deleteProduct($id) {
        DB::table('products')->delete($id);
    }

    public function getList($keyword,$company_id) {
        $products = DB::table('products')
        ->join('companies','products.company_id','=','companies.id')
        ->select(
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'companies.company_name',
        )
        ->orderBy('products.id', 'asc');

        if (!empty($keyword)) {
            $products->where('products.product_name','LIKE', '%'.$keyword.'%');
        }
        if (!empty($company_id)) {
            $products->where('products.company_id', $company_id);
        }
        return $products->get();
    }

}
