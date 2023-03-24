@extends('layouts.common')
@section('title', '商品登録画面')
@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2>商品登録フォーム</h2>
        <form method="POST" enctype="multipart/form-data" action="{{ route('product.store') }}" onSubmit="return checkSubmit()">
            @csrf
            <div class="form-group">
                <label for="company_id">
                    メーカー名
                </label>
                <select name="company_id">
                    <option selected="selected" value=""   class="{{old('select_placeholder')}}">メーカー名を選択してください</option>
                    @foreach ($selectItems as $selectItem)
                    <option id="company_id" name="company_id" value="{{ $selectItem->id }}">
                        {{ $selectItem->company_name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('company_id'))
                    <div class="text-danger">
                        {{ $errors->first('company_id') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="product_name">
                    商品名
                </label>
                <input name="product_name" class="form-control" value="{{ old('product_name') }}" type="text">
                @if ($errors->has('product_name'))
                    <div class="text-danger">
                        {{ $errors->first('product_name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="price">
                    価格
                </label>
                <input name="price" class="form-control" value="{{ old('price') }}" type="text">
                @if ($errors->has('price'))
                    <div class="text-danger">
                        {{ $errors->first('price') }}
                    </div>
                @endif
            </div>
            
            <div class="form-group">
                <label for="stock">
                    在庫
                </label>
                <input name="stock" class="form-control" value="{{ old ('stock') }}" type="txet">
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
                <textarea name="comment" class="form-control" rows="4"
                >{{ old('comment') }}</textarea>
                @if ($errors->has('comment'))
                <div class="text-danger">
                    {{$errors->first('comment') }}
                </div>
                @endif
            </div> 

            <div class="form-group">
                <label for="img_path">
                    画像
                </label>
                <input type="file" name="img_path" class="form-control-file">
            </div>

            <div class="mt-5">
                 <button type="button" class="btn btn-out-secondary" onclick="location.href='{{ route('product.list') }}'">
                    戻る 
                </a>
                <button type="submit" class="btn btn-primary">
                    登録する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection