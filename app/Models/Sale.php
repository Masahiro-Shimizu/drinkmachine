<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    //テーブル名
    protected $table = 'sales';

    //可変項目
    protected $fillable = 
    [
        'produuct_id',
    ];
    public function products()
    {
        return $this->belongsTo('App\Models\product');
    }

     //商品登録(販売時)
    public function purchase(Request $request)
    {        
        $inputs = $request->all();
        $Sale = Sale::create($inputs);
        $product_id = $inputs['product_id'];
        $quantity = $inputs['quantity'];
        $Product = Product::find($product_id);
        $productStock = Product::where('id','=',$product_id)->value('stock');

        $Sale->fill([
            'product_id' => $product_id ,                        
        ]);           
    
    if($productStock >= $quantity)
    {
        
    $Product->decrement('stock', $quantity);

    $Sale->save();
    $Product->save();
    }else{
        abort(500);
    }
    return array($Sale,$Product);

    }
}