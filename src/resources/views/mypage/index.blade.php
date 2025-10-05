@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')

<div class="profile-page">
    <div class="profile">
        <div class="profile__header">
            <div class="profile__image">
                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('images/default.png') }}" alt="プロフィール画面" class="image">
            </div>
            <div class="profile__info">
                <p class="profile__name">{{ $user->name }}</p>
            </div>
            <div class="profile__edit">
                <a href="/mypage/profile" class="profile__edit-btn">プロフィールを編集</a>
            </div>
        </div>
    </div>

    <div class="nav">
        <div class="nav__tabs">
        <a href="{{ route('mypage.index',['page' => 'sell']) }}" class="nav__tab {{ $tab === 'sell' ? 'nav__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="nav__tab {{ $tab === 'buy' ? 'nav__tab--active' : '' }}">購入した商品</a>
        </div>
    </div>

    <div class="product">
        <div class="product__list">
            @foreach ($itemsToShow as $item)
            <div class="product__item">
                <img src="{{ asset('images/' . $item->img_url) }}" alt="{{ $item->name }}">
                <p class="product__name">{{ $item->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection