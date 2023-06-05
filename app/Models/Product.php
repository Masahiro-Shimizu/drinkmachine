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

     /**
     * 検索処理
     */
    public function searchProducts($request) {
        // 検索フォームに入力された値を取得
        // 商品名
        $productName = $request->input('products.id');
        // メーカー名
        $companyName = $request->input('companies.company_name');
        // 価格(下限)
        $priceMin = $request->input('products.price');
        // 価格(上限)
        $priceMax = $request->input('products.price');
        // 在庫数(下限)
        $stockMin = $request->input('products.stock');
        // 在庫数(上限)
        $stockMax = $request->input('products.stock');
        
        $query = Product::query();
        // テーブル結合
        $query->join('companies', function ($query) use ($request) {
            $query->on('products.company_id', '=', 'companies.id');
            });
        // 一覧画面でソート可能にする
        //$query->sortable();
        // 商品名の検索条件(部分一致)
        if(!empty($productName)) {
            $query->where('products.product_name', 'LIKE', "%{$productName}%");
        }
        // メーカー名の検索条件
        if(!empty($companyName)) {
            $query->where('companies.company_name', 'LIKE', $companyName);
        }
        // 価格(下限)の検索条件
        if(!empty($priceMin) || $priceMax == "0") {
            $query->where('price', '>=', $priceMin);
        }
        // 価格(上限)の検索条件
        if(!empty($priceMax) || $priceMax == "0") {
            $query->where('price', '<=', $priceMax);
        }
        // 在庫数(下限)の検索条件
        if(!empty($stockMin) || $stockMin == "0") {
            $query->where('stock', '>=', $stockMin);
        }
        // 在庫数(上限)の検索条件
        if(!empty($stockMax) || $stockMax == "0") {
            $query->where('stock', '<=', $stockMax);
        }

        // 検索条件に一致するデータを全て取得
        $products = $query
        ->select('products.id', 'companies.id as company_id', 'companies.company_name', 'products.product_name',
        'products.price', 'products.stock', 'products.comment', 'products.img_path')
        ->orderBy("products.id")
        ->get();

        return $products;
    }

}
