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
                <div class="profile__rating">
                    <span class="star star--active">★</span>
                    <span class="star star--active">★</span>
                    <span class="star star--active">★</span>
                    <span class="star star--active">★</span>
                    <span class="star star--inactive">★</span>
                </div>
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
            <a href="{{ route('mypage.index', ['page' => 'trading']) }}" class="nav__tab {{ $tab === 'trading' ? 'nav__tab--active' : '' }}">取引中の商品</a>
        </div>
    </div>

    <div class="product">
        <div class="product__list">
            @forelse ($itemsToShow as $item)
            <div class="product__item">
                <img src="{{ asset('storage/images/' . $item->img_url) }}" alt="{{ $item->name }}">
                <p class="product__name">{{ $item->name }}</p>
            </div>
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