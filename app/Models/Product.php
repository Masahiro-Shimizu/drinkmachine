<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    //テーブルを指定
    protected  $table = 'products';


    protected $fillable = ['user_id', 'comment', 'img_path', 'product_name', 'price', 'stock', 'company_id'];



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

}