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
            ->sortable()
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

        $products = $query->sortable()->get();

        return  response()->json($products);
    }


    //削除
    public function exeDelete(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
        session()->flash('success', '商品を削除しました');
        return  response()->json($product);
    }


     /**
     * 商品登録画面
     * 
     * @return view
     */
    public function showCreate() {
        $selectItems = Company::all();

        return view('product.form', compact('selectItems'));
    }


    /**
     * 商品登録画面を表示する
     * ＠param ProductRequest $request
     * @return view
     */
    public function exeStore(ProductRequest $request){
        $product_instance = new Product;
        $img_path = $request->file('img_path');

        $path = null;
        if (!empty($img_path)) {
            $path = $img_path->store('\img', 'public');
        }
        
        $insert_data = [];
        $insert_data['company_id'] = $request->input('company_id');
        $insert_data['product_name'] = $request->input('product_name');
        $insert_data['price'] = $request->input('price');
        $insert_data['stock'] = $request->input('stock');
        $insert_data['comment'] = $request->input('comment');
        $insert_data['img_path'] = $path;

        \DB::beginTransaction();
        try {
            //商品を登録
            $product_instance->createProduct($insert_data);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
        \Session::flash('err_msg',config('messages.message2'));
        return redirect(route('product.list'));
    }
    
    /**
     * 商品詳細画面
     *  @param $id $message
     *  @return $view
     */
    public function showDetail($id) {
        $product_instans = new Product;
        $product = $product_instans->productDetail($id);

        try{
            if(is_null($product)) {
                \Session::flash('err_msg',config('messages.message1'));
                return redirect(route('product.list'));
            }
        }catch(\Throwable $e){
            throw new \Exception($e->getMessage());
        }
        return view('product.detail',compact('product'));
    }

    /**
     * 商品編集フォーム画面
     *  @param $id
     *  @return $view
     */
    public function showEdit($id) {
        $product_instance = new Product;
        $company_instance = new Company;

        try{
            $product = $product_instance->productDetail($id);
            $company_list = $company_instance->companyList();
            if(is_null($product)) {
                \Session::flash('err_msg',config('messages.message3'));
                return redirect(route('product.list'));
            }
        }catch(\Throwable $e){
            throw new \Exception($e->getMessage());
        }
        return view('product.edit',compact('product','company_list'));
    }

    /**
     * 商品編集フォーム
     * @param ProductRequest $request
     * @return view
     */
    public function exeUpdate(ProductRequest $request){
        $product_instance = new Product;
        $img_path = $request->file('img_path');

        $path = null;
        if (!empty($img_path)) {
            $path = $img_path->store('\img', 'public');
        }
        
        $update_date = [];
        $update_date['id'] = $request->input('id');
        $update_date['company_id'] = $request->input('company_id');
        $update_date['product_name'] = $request->input('product_name');
        $update_date['price'] = $request->input('price');
        $update_date['stock'] = $request->input('stock');
        $update_date['comment'] = $request->input('comment');
        $update_date['img_path'] = $path;

        \DB::beginTransaction();
        try {
            $product_instance->updateProduct($update_date);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
        \Session::flash('err_msg',config('messages.message3'));
        return redirect(route('product.list'));
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