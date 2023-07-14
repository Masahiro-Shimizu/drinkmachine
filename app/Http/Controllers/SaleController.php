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
    try {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($product->stock < $quantity) {
            return response()->json([config('messages.message5')], 422);
        }

        DB::beginTransaction();

        for ($i = 0; $i < $quantity; $i++) {
            $sale = new Sale();
            $sale->product_id = $request->product_id;
            $sale->created_at = now();
            $sale->updated_at = now();
            $sale->save();

            $product->decrement('stock');
        }

        DB::commit();

        return response()->json([config('messages.message6')]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([config('messages.message7')], 500);
    }
    }

    public function increase(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
    
            DB::beginTransaction();
    
            for ($i = 0; $i < $quantity; $i++) {
                $product->increment('stock');
            }
    
            DB::commit();
    
            return response()->json([config('messages.message8')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([config('messages.message7')], 500);
        }
    
    }

}