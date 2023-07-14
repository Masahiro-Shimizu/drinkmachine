<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductEditRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
use ProductsTableSeeder;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //商品一覧
    public function showList(Request $request)
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

        $products = $query->get();

        return  response()->json($products);
    }

    /**
     * Show the application dashboard.
     * 商品一覧をソート
     * 
     */
    public function sortId(Request $request)
    {        
        $product = new Product;
        $productSortId = $product->productSortId($request);
    
    return ($productSortId);
    }

    public function sortProduct_name(Request $request)
    {        
        $product = new Product;
        $productSortProduct_name = $product->productSortProduct_name($request);

    return ($productSortProduct_name);
    }


    public function sortPrice(Request $request)
    {        
        $product = new Product;
        $productSortPrice = $product->productSortPrice($request);

    return ($productSortPrice);
    }


    public function sortStock(Request $request)
    {        
        $product = new Product;
        $productSortStock = $product->productSortStock($request);

        return ($productSortStock);
    }


    public function sortCompany_name(Request $request)
    {       
        $product = new Product;
        $productSortCompany_name = $product->productSortCompany_name($request);
 
        return ($productSortCompany_name);
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
    public function showEdit($id)
    {
        $Product = Product::with('company')->find($id);
        $Companies = Company::all();  
        $Products = Product::all();
        
         

        return view('product.edit',['Product' => $Product, 'Companies' => $Companies, 'Products' => $Products]);
    }

    /**
     * 商品編集フォーム
     * @param ProductRequest $request
     * @return view
     */
    public function exeUpdate(ProductRequest $request)  {
        
        $inputs = $request->all();
        $Product = Product::with('company')->find($inputs['id'],);
        \DB::beginTransaction();
        try {
        $products = new Product;
        $productEdit = $products->productEdit($request);
    
        \DB::commit();
        } catch(\Throwable $e) {
        \DB::rollback();    
        abort(500);
        }   
        \Session::flash('err_msg',config('messages.message2'));
         
        return redirect(route('product.edit', $Product->id ));
    }

}