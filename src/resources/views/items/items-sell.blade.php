@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/items-sell.css') }}">
@endsection

@section('content')
<header class="header">
    <div class="header__inner">
        <a href="/" class="header__logo">
            <img src="{{ asset('images/logo.svg') }}" alt="CoachTechLogo">
        </a>
        <div class="header__item">
            <div class="item-search">なにをお探しですか？</div>
        </div>
        <div class="header__button">
            <a href="" class="button-logout">ログアウト</a>
            <a href="" class="button-mypage">マイページ</a>
            <a href="" class="button-sell">出品</a>
        </div>
    </div>
</header>

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
                    <button type="button" data-id="ファッション">ファッション</button>
                    <button type="button" data-id="家電">家電</button>
                    <button type="button" data-id="インテリア">インテリア</button>
                    <button type="button" data-id="レディース">レディース</button>
                    <button type="button" data-id="メンズ">メンズ</button>
                    <button type="button" data-id="コスメ">コスメ</button>
                    <button type="button" data-id="本">本</button>
                    <button type="button" data-id="ゲーム">ゲーム</button>
                    <button type="button" data-id="スポーツ">スポーツ</button>
                    <button type="button" data-id="キッチン">キッチン</button>
                    <button type="button" data-id="ハンドメイド">ハンドメイド</button>
                    <button type="button" data-id="アクセサリー">アクセサリー</button>
                    <button type="button" data-id="おもちゃ">おもちゃ</button>
                    <button type="button" data-id="ベビー・キッズ">ベビー・キッズ</button>
                </div>
                <input type="hidden" name="categories" id="categories">
            </div>

            <div class="section-condition">
                <label for="condition" class="field-title">商品の状態</label>
                <select name="condition" id="condition" class="condition">
                    <option value="" disabled selected>選択してください</option>
                    <option value="良好">良好</option>
                    <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                    <option value="状態が悪い">状態が悪い</option>
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
