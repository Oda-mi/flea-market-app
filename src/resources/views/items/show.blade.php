@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="item">
    <div class="item__image">
        <img src="{{ asset('images/' . $item->img_url) }}" alt="{{ $item->name }}">
    </div>
    <div class="item__info">
        <h1 class="item__name">{{ $item->name }}</h1>
        <p class="item__brand">{{ $item->brand }}</p>
        <p class="item__price">&yen;{{ $item->price }}（税込）</p>
        <div class="item__actions">
            <button class="btn-favorite">☆</button>
            <button class="btn-comment">💭</button>
        </div>
        <a href="{{ route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>

        <div class="item__details">
            <h3>商品の説明</h3>
            <p>{{ $item->description }}</p>
            <h3>商品の情報</h3>
            <div class="detail-category">
                <div>カテゴリー</div>
                <div>{{ $item->category }}</div>
            </div>

            <div class="detail-condition">
                <div>商品の状態</div>
                <div>{{ $item->condition->name }}</div>
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