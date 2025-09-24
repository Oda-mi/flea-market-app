@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/items-show.css') }}">
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

<div class="item">
    <div class="item__image">
        <img src="{{ asset('images/coffee.jpg') }}" alt="商品画像">
    </div>
    <div class="item__info">
        <h1 class="item__name">商品名</h1>
        <p class="item__brand">ブランド名</p>
        <p class="item__price">¥12,345（税込）</p>
        <div class="item__actions">
            <button class="btn-favorite">☆</button>
            <button class="btn-comment">💭</button>
        </div>
        <button class="btn-purchase">購入手続きへ</button>

        <div class="item__details">
            <h3>商品の説明</h3>
            <p>ここに商品説明が入る</p>
            <h3>商品の情報</h3>
            <div class="detail-category">
                <div>カテゴリー</div>
                <div>カテゴリが表示される</div>
            </div>

            <div class="detail-condition">
                <div>商品の状態</div>
                <div>新品or中古品が表示される</div>
            </div>
        </div>

        <div class="item__comments">
            <h3>コメント</h3>
            <div class="comment">
                <img src="" alt="プロフィール画像" class="comment-profile">
                <strong>ユーザー名</strong>
            </div>
            <p class="comment-text">コメント内容がここに入る</p>

            <div class="comment-input">
                <h3>商品へのコメント</h3>
                <textarea name="" id="" placeholder="コメントを入力"></textarea>
                <button class="btn-submit-comment" type="submit">コメントを送信する</button>
            </div>
        </div>
    </div>
</div>

@endsection