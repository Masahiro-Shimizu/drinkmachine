<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{

    //テーブルを指定
    protected  $table = 'products';
    use Sortable;

    protected $fillable = ['user_id', 'comment', 'img_path', 'product_name', 'price', 'stock', 'company_id'];
    protected $sortable = ['product_id','product_name', 'company_id'];


    public function getList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();
        return $products;
    }

    //リレーションを設定
    public function company() {
        return $this->belongsTo('App\Models\Company');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function isSoldOut() {
        return $this->hasMany ('App\Models\Sale')->count() >0 ;
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


}