<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function buy(Request $request,$id)
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

        return response()->json(Sale::all());
    }

    public function sale(Request $request)
    {
        $sale = new Sale();
        $sale->id = $request->id;
        $sale->product_id = $request->product_id;
        $sale->save();
        return response()->json(Sale::all());
    }


}