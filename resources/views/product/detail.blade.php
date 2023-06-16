<!--
①共通テンプレを継承したリストを作る
②リンクを作る
③routeを作る
④（V)詳細画面を作る
-->
@extends('layouts.common')
@section('title', '商品詳細画面')
@section('list')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <span>商品ID: {{ $product->id }}</span>
            <br>
            <span>商品画像:
                @if($product->img_path === null)
                <img class="w-25"src="/storage/noimage.png">
                @else
                <img class="w-25"src="{{ asset( 'storage'.$product->img_path) }}">
                @endif
            </span>
            <br>
            <span>商品名:{{ $product->product_name }}</span>
            <br>
            <span>メーカー:{{ $product->company_name }}</span>
            <br>
            <span>価格:{{ $product->price }}</span>
            <br>
            <span>在庫:{{ $product->stock }}</span>
            <br>
            <span>コメント:{{ $product->comment }}</span>
        </div>
    <div class="mt-5">
    <button type="button" class="btn btn-primary" onclick="location.href='/product/edit/{{ $product->id }}'">
         編集
    </button>
        </div>
    </div>
@endsection