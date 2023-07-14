<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductEditRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //商品一覧
    public function showList(Request $request)
    {
        $product_instance = new Product;
        $company_data = Company::all();
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');


        $product_list = $product_instance->getList($keyword, $company_id);

        return view('product.list', compact('product_list', 'company_data', 'keyword', 'company_id'));
    }

    /**
     * 商品詳細画面
     *  @param $id
     *  @return $view
     */
    public function showDetail($id) {
        $product_instans = new Product;
        $product = $product_instans->productDetail($id);

        try{
            if(is_null($product)) {
                \session::flash('err_msg','データがありません。');
                return redirect(route('product.list'));
            }
        }catch(\Throwable $e){
            throw new \Exception($e->getMessage());
        }
        return view('product.detail',compact('product'));
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
                \session::flash('err_msg','データがありません。');
                return redirect(route('product.list'));
            }
        }catch(\Throwable $e){
            throw new \Exception($e->getMessage());
        }
        return view('product.edit',compact('product','company_list'));
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

    }

    /**
     * 商品情報削除
     * ＠param $id
     */
    public function exeDelete($id)
    {
        $product_instance = new Product;
        if(empty($id)){
            \Session::flash('err_msg','該当データはありません');
            return redirect(route('product.list'));
        }

        \DB::beginTransaction();
        try{
            $product_instance->deleteProduct($id);
            \DB::commit();
        }catch(\Throwable $e){
            throw new \Exception($e->getMessage());
            \DB::rollback();
        }
        \Session::flash('err_msg','削除しました。');

        return redirect(route('product.list'));
    }

}