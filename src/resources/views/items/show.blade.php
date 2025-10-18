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

        <div class="item-actions">
            <div class="item-actions__favorite">
                <form action="{{ route('items.toggleFavorite', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="item-actions__favorite-btn">
                        @if (auth()->check() && auth()->user()->favorites->contains($item->id))
                        <img src="{{ asset('images/star-icon-active.png') }}" alt="お気に入り" class="item-actions__favorite-icon">
                        @else
                        <img src="{{ asset('images/star-icon.png') }}" alt="お気に入り" class="item-actions__favorite-icon">
                        @endif
                    </button>
                </form>
                <span class="item-actions__favorite-count">{{ $item->favorites->count() }}</span>
            </div>
            <div class="item-actions__comment">
                <img src="{{ asset('images/comment-icon.png') }}" alt="コメント" class="item-actions__favorite-comment">
                <span class="item-actions__comment-count">{{ $item->comments->count() }}</span>
            </div>
        </div>

        <div class="item-purchase">
            <a href="{{ $item->purchase ? 'javascript:void(0);' : route('purchase.index', $item->id) }}" class="item-purchase__btn">購入手続きへ</a>
        </div>


        <div class="item-details">
            <div class="item-details__description">
                <h3>商品の説明</h3>
                <p>{!! nl2br(e($item->description)) !!}</p>
            </div>
            <div class="item-detail__info">
                <h3>商品の情報</h3>
                <div class="item-details__category">
                    <div class="item-details__category-label">カテゴリー</div>
                    <div class="item-details__category-list">
                        @foreach ($item->categories as $category)
                        <span>{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="item-details__condition">
                    <div class="item-details__condition-label">商品の状態</div>
                    <div class="item-details__condition-list">{{ $item->condition->name }}</div>
                </div>
            </div>
        </div>

        <div class="item-comments">
            <h3>コメント（{{ $item->comments->count() }}）</h3>
            @foreach ($item->comments as $comment)
            <div class="item-comments__comment">
                <div class="item-comments__user">
                    <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default.png') }}" alt="プロフィール画像" class="item-comments__user-img">
                    <strong>{{ $comment->user->name }}</strong>
                </div>
                <p class="item-comments__text">{!! nl2br(e($comment->comment)) !!}</p>
            </div>
            @endforeach

            <div class="item-comments__form">
                <h3>商品へのコメント</h3>
                <form action="{{ route('comment.store', $item->id) }}" method="post">
                    @csrf
                    <textarea name="comment" id="" placeholder="コメントを入力"></textarea>
                    <div class="item-comments__error">
                    @error('comment')
                    {{ $message }}
                    @enderror
                    <button class="item-comments__submit-btn" type="submit"
                    @if (!auth()->check() || $item->purchase)
                    disabled
                    @endif>
                    コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
