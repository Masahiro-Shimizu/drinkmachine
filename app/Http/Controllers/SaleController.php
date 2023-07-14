<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function buy(Request $request)
    {
        $query = Product::query();
        global $products;
        $products = $query->where('id', $request->product_id)->get();
        $stock = Product::select('stock')->count();
        if ($stock < 0){
            return false;
        }

        DB::beginTransaction();
        try {
            $sale = new Sale();
            $sale->product_id = $request->product_id;
            $sale->created_at = now();
            $sale->updated_at = now();
            $sale->save();

            DB::table('products')->decrement('stock');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        //return response()->json(Sale::all());
        return response()->json(['message' => '在庫がありません'], 422, ['Content-Type' => 'application/json'], Sale::all(),JSON_UNESCAPED_UNICODE);
    }

    public function sale(Request $request)
    {
        $products = \DB::table('products')
        ->get();
        //$sort = $request->sort;
        $order = $request->order;
        $orderpram = "desc";
        return view('product.list', [
            'companies' => Company::all(),
            'products' => $products,
            'order' => $orderpram
        ]);
        return response()->json(Sale::all());
    }


}