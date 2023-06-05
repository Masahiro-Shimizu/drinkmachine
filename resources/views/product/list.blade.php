<!--
①共通テンプレ(common.blade.php)を作る
②共通ヘッダーを作る
③共通フッターを作る
-->

@extends('layouts.common')
@section('title', '商品一覧')
@section('list')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>商品一覧</h2>
            <div class="form-group mt-3">
                <form method="GET" action="{{ route('product.list') }}" id="searchForm" class="form-inline my-2 my-lg-0">
                    <div class="search-form">
                    <label for="productName">商品名</label>
                            <div class="col-auto">
                                <input type="text" class="form-control" id="productName" name="productName">
                            </div>
                        </label>
                    
                    <div class="search_company-name">
                        <select name="company_id">
                            <option selected="select_name" value="" class="select_placeholder" >メーカーを選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class ="search-btn ml-2">
                        <button class="btn btn-secondary" type="button" id="searchButton">検索</button>
                    </div>
                </form>
            </div>
            @if ('err_msg')
            <p class ="text-danger">{{ session('err_msg') }}</p>
            @endif

            <table class="table table-striped" id="resultTable">
                    <thead>
                        <tr>
                            <th>@sortablelink ('id', '商品ID')</th>
                            <th>@sortablelink ('img_path', '商品画像')</th>
                            <th>@sortablelink ('product_name', '商品名')</th>
                            <th>@sortablelink ('price', '価格')</th>
                            <th>@sortablelink ('stock', '在庫数')</th>
                            <th>@sortablelink ('comment', 'コメント')</th>
                            <th>@sortablelink ('company_name', 'メーカー名')</th>
                            <th>詳細表示ボタン</th>
                            <th>削除ボタン</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path !=='')
                        <img src="{{ asset('storage/'.$product->img_path)}}">
                        @else
                        <p>no image</p>
                        @endif
                    </td>
                    <td id="resultProductName">{{ $product->product_name }}</td>
                    <td id="resultPrice">{{ $product->price }}</td>
                    <td id="resultStock">{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    <td id="resultCompanyName">{{ $product->company_name }}</td>
                    <td><a href="{{ route('product.detail', ['id'=>$product->id]) }}" class="btn btn-primary">詳細表示</a></td>
                    <td><input type="button" class="btn btn-danger" id="deleteButton" value="削除"></td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection