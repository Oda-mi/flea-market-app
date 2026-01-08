@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')

<div class="profile">
    <div class="profile__header">
        <div class="profile__image">
            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('/images/default.png') }}" alt="プロフィール画面" class="image">
        </div>
        <div class="profile__info">
            <div class="profile__name-area">
                <p class="profile__name">{{ $user->name }}</p>
                @if ($averageRating > 0)
                    <div class="profile__rating">
                        @for ($starNumber = 1; $starNumber <= 5; $starNumber++)
                            <span class="star {{ $starNumber <= $averageRating ? 'star--active' : 'star--inactive' }}">★</span>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
        <div class="profile__edit">
            <a href="/mypage/profile" class="profile__edit-btn">プロフィールを編集</a>
        </div>
    </div>


    <div class="nav">
        <div class="nav__tabs">
            <a href="{{ route('mypage.index',['page' => 'sell']) }}" class="nav__tab {{ $tab === 'sell' ? 'nav__tab--active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="nav__tab {{ $tab === 'buy' ? 'nav__tab--active' : '' }}">購入した商品</a>
            <a href="{{ route('mypage.index', ['page' => 'trading']) }}" class="nav__tab {{ $tab === 'trading' ? 'nav__tab--active' : '' }}">
                取引中の商品
                @if($totalUnreadCount > 0)
                    <span class="nav__unread-count">{{ $totalUnreadCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <div class="product">
        <div class="product__list">
            @forelse ($tab === 'trading' ? $transactions : $itemsToShow as $itemOrTransaction)
                @if($tab === 'trading')
                    {{-- 取引中タブの場合 --}}
                    <a href="{{ route('transactions.show', ['id' => $itemOrTransaction->id]) }}" class="product__list-link">
                        <div class="product__item">
                            <div class="product__image-wrapper">
                                <img src="{{ asset('storage/images/' . $itemOrTransaction->item->img_url) }}" alt="{{ $itemOrTransaction->item->name }}">
                                @if($itemOrTransaction->unread_count > 0)
                                    <span class="product__unread-count">{{ $itemOrTransaction->unread_count }}</span>
                                @endif
                            </div>
                            <p class="product__name">{{ $itemOrTransaction->item->name }}</p>
                        </div>
                    </a>
                @else
                    {{-- 出品 or 購入タブの場合 --}}
                    <div class="product__item">
                        <img src="{{ asset('storage/images/' . $itemOrTransaction->img_url) }}" alt="{{ $itemOrTransaction->name }}">
                        <p class="product__name">{{ $itemOrTransaction->name }}</p>
                    </div>
                @endif
            @empty
                @if ($tab === 'sell')
                    <p class="product__empty-message">出品した商品はありません</p>
                @elseif ($tab === 'buy')
                    <p class="product__empty-message">購入した商品はありません</p>
                @elseif ($tab === 'trading')
                    <p class="product__empty-message">取引中の商品はありません</p>
                @endif
            @endforelse
        </div>
    </div>
</div>

@endsection