@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="item">
    <div class="item__image">
        <img src="{{ asset('storage/images/' . $item->img_url) }}" alt="{{ $item->name }}">
    </div>
    <div class="item__info">
        <div class="item__title-sold">
            <h1 class="item__name">{{ $item->name }}</h1>
            @if ($item->purchase)
            <p class="item__sold">Sold</p>
            @endif
        </div>
        <p class="item__brand">{{ $item->brand }}</p>
        <p class="item__price">&yen;<span>{{ number_format($item->price) }}</span>（税込）</p>
        <div class="item__actions">
            <button class="btn-favorite">☆</button>
            <button class="btn-comment">💭</button>
        </div>
        <a href="{{ $item->purchase ? 'javascript:void(0);' : route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>



        <div class="item__details">
            <div class="detail-description">
                <h3>商品の説明</h3>
                <p>{!! nl2br(e($item->description)) !!}</p>
            </div>
            <div class="detail-info">
                <h3>商品の情報</h3>
                <div class="detail-category">
                    <div class="category-label">カテゴリー</div>
                    <div class="category-list">
                        @foreach ($item->categories as $category)
                        <span>{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="detail-condition">
                    <div class="condition-label">商品の状態</div>
                    <div class="condition-list">{{ $item->condition->name }}</div>
                </div>
            </div>
        </div>

        <div class="item__comments">
            <h3>コメント（{{ $item->comments->count() }}）</h3>
            @foreach ($item->comments as $comment)
            <div class="comment">
                <div class="comment__user">
                    <img src="{{ $comment->user->profile_img ? asset('storage/' . $comment->user->profile_image) : asset('images/default.png') }}" alt="プロフィール画像" class="comment-profile">
                    <strong>{{ $comment->user->name }}</strong>
                </div>
                <p class="comment-text">{!! nl2br(e($comment->comment)) !!}</p>
            </div>
            @endforeach

            <div class="comment-input">
                @auth
                <h3>商品へのコメント</h3>
                <form action="{{ route('comment.store', $item->id) }}" method="post">
                    @csrf
                    <textarea name="comment" id="" placeholder="コメントを入力"></textarea>
                    <div class="comment__error">
                    @error('comment')
                    {{ $message }}
                    @enderror
                    <button class="btn-submit-comment" type="submit"
                    @if ($item->purchase)
                    disabled
                    @endif>
                    コメントを送信する</button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</div>

@endsection