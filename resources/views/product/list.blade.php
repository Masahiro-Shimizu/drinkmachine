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
                    <input type="text" class="form-control mr-sm-2" name="keyword"
                        placeholder="キーワードを入力" value="{{ $keyword }}">
                    
                    <div class="search_company-name">
                        <select name="company_id">
                            <option selected="select_name" value="" class="select_placeholder" >メーカーを選択してください</option>
                            @foreach ($company_data as $company_list)
                            @if ($company_id == $company_list->id)
                            <option value="{{ $company_list->id }}" selected>{{ $company_list->company_name }}</option>
                            @else
                            <option value="{{ $company_list->id }}">{{ $company_list->company_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class ="search-btn ml-2">
                        <button class="btn btn-secondary" type="submit">検索</button>
                    </div>
                </form>
            </div>
            @if ('err_msg')
            <p class ="text-danger">{{ session('err_msg') }}</p>
            @endif

            <table class="table table-striped">
                <tr>
                    <th>商品ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($product_list as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td> 
                        @if ($product->img_path === null)
                            <img class="w-25 h-25" src="/storage/noimage.png">
                        @else
                            <img class="w-25 h-25" src="{{ asset( '/storage'.$product->img_path) }}">
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}円</td>
                    <td>{{ $product->stock}}個</td>
                    <td>{{ $product->company_name}}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-info"
                            onclick="location.href='/product/{{ $product->id }}'">詳細</button>
                    </td>
                    <form method="POST" action="{{route('product.delete', $product->id )}}" onSubmit="return checkDelate()">
                        @csrf
                        <td>
                            <button type="submit" class="btn btn-danger" onclick="">削除</button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection