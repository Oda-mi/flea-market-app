@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')

<div class="sell__content">  {{--sell売る--}}
    <div class="sell__heading">
        <h2>商品出品</h2>
    </div>

    {{--enctype="multipart/form-data"画像送信に必須--}}
    <form action="" method="post" enctype="multipart/form-data" class="sell__form">
        @csrf

        <div class="section">
            <label for="product_image" class="field-title">商品画像</label>
            <div class="image-upload">
                <input type="file" name="image" id="product_image" class="product-image">
                <label for="product_image" class="image-btn">画像を選択する</label>
            </div>
        </div>

        <div class="section">
            <label class="section-title">商品の詳細</label>

            <div class="section-category">
                <label for="categories" class="field-title">カテゴリー</label>
                <div class="categories">
                    @foreach ($categories as $category)
                        <button type="button" data-id="{{ $category->name }}">{{ $category->name }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="categories" id="categories">
            </div>

            <div class="section-condition">
                <label for="condition" class="field-title">商品の状態</label>
                <select name="condition" id="condition" class="condition">
                    <option value="" disabled selected>選択してください</option>
                    @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="section">
            <label class="section-title">商品名と説明</label>
            <div class="form-group">
                <label for="name" class="field-title">商品名</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="form-group">
                <label for="brand" class="field-title">ブランド名</label>
                <input type="text" name="brand" id="brand">
            </div>
            <div class="form-group">
                <label for="description" class="field-title">商品の説明</label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div class="form-group">
                <label for="price" class="field-title">販売価格</label>
                <div class="price-input">  {{--￥マークを表示させるためのラッパー--}}
                    <input type="number" name="price" id="price" class="no-spin">
                </div>
            </div>
        </div>
        <div class="section-button">
            <button class="btn-submit" type="submit">出品する</button>
        </div>
    </form>
</div>
@endsection


<!-- カテゴリーボタン選択時色変わる処理未設定 -->
