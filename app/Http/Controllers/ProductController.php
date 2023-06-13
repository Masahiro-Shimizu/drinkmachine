<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductEditRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //商品一覧
    public function showList(Request $request)
    {
        $products = \DB::table('products')->get();
        $products = Product::sortable()->get();
        return view('product.list', [
            'companies' => Company::all(),
            'products' => $products
        ]);
    }

    //検索機能
    public function searchProducts(Request $request)
    {
        //検索フォームに入力された値を取得
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');
        $price = $request->input('price');
        $stock = $request->input('stock');
        $from_price = $request->input('from_price');
        $to_price = $request->input('to_price');
        $from_stock = $request->input('from_stock');
        $to_stock = $request->input('to_stock');

        $query = Product::query();

        $query->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name');

        //メーカー検索
        if (!empty($company_id)) {
            $query->where('company_id', $company_id);
        }
        //商品名検索
        if (!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }
        //価格検索
        if (!empty($from_price)) {
            $query->where('price', '>=', $from_price);
        }

        if (!empty($to_price)) {
            $query->where('price', '<=', $to_price);
        }

        //在庫検索
        if (!empty($from_stock)) {
            $query->where('stock', '>=', $from_stock);
        }

        if (!empty($to_stock)) {
            $query->where('stock', '<=', $to_stock);
        }

        $products = $query->get();

        return  response()->json($products);
    }


    //削除
    public function destroy(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
        session()->flash('success', '商品を削除しました');
        return  response()->json($product);
    }


    //新規出品フォーム
    public function create()
    {
        return view('products.create', [
            'companies' => Company::all(),
        ]);
    }


    // 商品追加処理
    public function store(ProductRequest $request, FileUploadService $service)
    {
        // //画像投稿処理
        if (isset($img_path)) {
            $file_name = $request->file('img_path')->getClientOriginalName();
            $path = $request->img_path->storeAs('public/images', $file_name);
            $save_path = str_replace('public/images/', '', $path);
        } else {
            $save_path = "";
        }

        Product::create([
            'user_id' => \Auth::user()->id,
            'product_name' => $request->product_name,
            'comment' => $request->comment,
            'price' => $request->price,
            'stock' => $request->stock,
            'img_path' => $save_path,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('products.index', \Auth::user());
    }


    //商品詳細
    public function show($id)
    {
        $product = Product::find($id);
        return view('products.show', [
            'product' => $product
        ]);
    }

    //商品編集フォーム
    public function edit($id)
    {
        $product = Product::find($id);
        return view('products.edit', [
            'product' => $product,
            'companies' => Company::all()
        ]);
    }

    //商品更新処理
    public function update($id, ProductEditRequest $request, FileUploadService $service)
    {
        $product = Product::find($id);
        $product->update(
            $request->only([
                'id', 'product_name',  'company_name', 'price', 'stock', 'comment', 'img_path'
            ])
        );

        $file_name = $request->file('img_path')->getClientOriginalName();
        $path = $request->img_path->storeAs('public/images', $file_name);
        $save_path = str_replace('public/images/', '', $path);
        if ($product->img_path !== '') {
            \Storage::disk('public')->delete($product->img_path);
        }
        $product->update([
            'img_path' => $save_path, // ファイル名を保存
        ]);
        return redirect()->route('products.edit', $product);
    }

    //購入処理
    public function purchase(SaleRequest $request)
    {
        $sale = Sale::create([
            'user_id' => \Auth::user()->id,
            'product_id' => $request->id,
        ]);
        return redirect()->route('products.purchase', $request->id);
    }
}