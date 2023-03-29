<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;

class ProductController extends Controller
{
    // ①（M)Product Modelを呼び出す
    // ②（C)ContorollerからBladeに渡す
    // ③ (V)Bladeで表示する
    /**
    * コンストラクタ
    * 継承したControllerクラスのmiddleware()を利用する
    */
    public function __construct() {
    // ログイン状態を判断するミドルウェア
    $this->middleware('auth');
    }
        
    /**
     * 商品一覧を表示する
     * 
     * @return view
     */
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
        \Session::flash('err_msg','商品を登録しました。');

        return redirect(route('product.list'));
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
        \Session::flash('err_msg','商品情報を更新しました。');

        return redirect(route('product.list'));
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
