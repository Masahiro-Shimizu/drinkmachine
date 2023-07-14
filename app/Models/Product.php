<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{

    //テーブルを指定
    protected  $table = 'products';

    protected $fillable = ['user_id', 'comment', 'img_path', 'product_name', 'price', 'stock', 'company_id'];
    public $sortable = ['products'];


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

    public function productSortId(Request $request)
    {        
        $sort = $request->all();
        
        $id = $sort['id'];
        

        if(!empty($id)){
        if ($id){
            if($id == '1'){
                $products = Product::with('Company')->orderBy('id')->get();
            }elseif($sort['id'] == '2'){
                $products = Product::with('Company')->orderBy('id','DESC')->get();
            }
        }
    }
    
    return ($products);
    }
             

    public function productSortProduct_name(Request $request)
    {        
        $sort = $request->all();
        
        $product_name = $sort['product_name'];
        
        if(!empty($product_name)){
        if ($product_name){
            if($product_name == '5'){
                $products = Product::with('Company')->orderBy('product_name')->get();
            }elseif($sort['product_name'] == '6'){
                $products = Product::with('Company')->orderBy('product_name','DESC')->get();
            }
        }
    }

    return ($products);
    }

    public function productSortPrice(Request $request)
    {        
        $sort = $request->all();
           
        $price = $sort['price'];


    if(!empty($price)){
        if ($price){
            if($price == '7'){
                $products = Product::with('Company')->orderBy('price')->get();
            }elseif($sort['price'] == '8'){
                $products = Product::with('Company')->orderBy('price','DESC')->get();
            }
        }
    }

    return ($products);
    }


    public function productSortStock(Request $request)
    {        
        $sort = $request->all();
    
        $stock = $sort['stock'];
        

    if(!empty($stock)){
        if ($stock){
            if($stock == '9'){
                $products = Product::with('Company')->orderBy('stock')->get();
            }elseif($sort['stock'] == '10'){
                $products = Product::with('Company')->orderBy('stock','DESC')->get();
            }
        }
    }

    return ($products);
    }

    public function productSortCompany_name(Request $request)
    {        
        $sort = $request->all();
        
        $company_name = $sort['company_name'];

    if(!empty($company_name)){
        if ($company_name){
            if($company_name == '11'){
                $products = Product::select('products.id','companies.id as company_id','products.product_name','products.price','products.stock','products.img_path','companies.company_name')
        ->join('companies','products.company_id','=','companies.id')->orderBy('company_name')
        ->get();
            }elseif($company_name == '12'){
                $products = Product::select('products.id','companies.id as company_id','products.product_name','products.price','products.stock','products.img_path','companies.company_name')
        ->join('companies','products.company_id','=','companies.id')->orderBy('company_name','DESC')
        ->get();
            }
        }
    }

        return ($products);
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