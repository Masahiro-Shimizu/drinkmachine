<!--
    ①route作成(編集ボタン)
    ②Controllerづくり
    ③編集フォーム（View)づくり
    ④データ更新機能（Model)づくり
-->
@extends('layouts.common')
@section('title', '商品編集画面')
@section('list')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2>商品情報編集フォーム</h2>
        <form method="POST" action="{{ route('product.update') }}" enctype="multipart/form-data" onSubmit="return checkSubmit()">
            @csrf
            @foreach($Companies as $Company)
            <input type="hidden" name="id" value="{{ $Company->id }}">
            @endforeach
            @foreach($Products as $Products)
            <input type="hidden" name="id" value="{{ $Products->company->id }}">
            @endforeach
            <input type="hidden" name="id" value="{{ $Product->id }}">
            <div class="form-group">
                <label for="product_name">
                    商品名
                </label>
                <input
                    id="product_name"
                    name="product_name"
                    class="form-control"
                    value="{{ $Product->product_name }}"
                    type="text"
                >
                @if ($errors->has('product_name'))
                    <div class="text-danger">
                        {{ $errors->first('product_name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="company_name">
                    メーカー
                </label>
                <select  id="company_name" class="form-control" name="company_name" value="">
                <option value="{{ $Products->company->id }}" style='display:none;' selected>{{ $Products->company->company_name }}</option>
                @foreach($Companies as $Company)
                <option value="{{ $Company->id }}">{{ $Company->company_name }}</option>
                @endforeach
                </select>
                
            </div>

            <div class="form-group">
                <label for="price">
                    価格
                </label>
                <input
                    id="price"
                    name="price"
                    class="form-control"
                    value="{{ $Product->price }}"
                    type="text"
                >
                @if ($errors->has('price'))
                    <div class="text-danger">
                        {{ $errors->first('price') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="stock">
                    在庫数
                </label>
                <input
                    id="stock"
                    name="stock"
                    class="form-control"
                    value="{{ $Product->stock }}"
                    type="text"
                >
                @if ($errors->has('stock'))
                    <div class="text-danger">
                        {{ $errors->first('stock') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="comment">
                    コメント
                </label>
                <textarea
                    id="comment"
                    name="comment"
                    class="form-control"
                    rows="2"
                >{{ $Product->comment }}</textarea>
                @if ($errors->has('comment'))
                    <div class="text-danger">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                
            <input type="file" 
                       id="img_path" 
                       name="img_path" 
                       value="{{ $Product->img_path }}">
                     
            	
                @if ($errors->has('img_path'))
                    <div class="text-danger">
                        {{ $errors->first('img_path') }}
                    </div>
                @endif
            </div>

            <div class="mt-5">
                <a class="btn btn-secondary" href= '/list/{{ $Product->id }}'>
                    戻る
                </a>
                <button type="submit" class="btn btn-primary">
                    更新
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function checkSubmit(){
if(window.confirm('更新してよろしいですか？')){
    return true;
} else {
    return false;
}
}
</script>
@endsection