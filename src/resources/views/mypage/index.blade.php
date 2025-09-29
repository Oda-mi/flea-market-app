@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')

<div class="profile-page">
    <div class="profile">
        <div class="profile__header">
            <div class="profile__image">
                <img src="{{ asset('images/user.jpg') }}" alt="プロフィール画面" class="image">
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
        <a href="" class="nav__tab nav__tab--active">出品した商品</a>
        <a href="" class="nav__tab">購入した商品</a>
        </div>
    </div>

    <div class="product">  {{--商品--}}
        <div class="product__list">
            <div class="product__item">
                <img src="{{ asset('images/clock.jpg') }}" alt="商品１">
                <p class="product__name">腕時計</p>
            </div>
            <div class="product__item">
                <img src="{{ asset('images/onion.jpg') }}" alt="商品２">
                <p class="product__name">たまねぎ</p>
            </div>
            <div class="product__item">
                <img src="{{ asset('images/mic.jpg') }}" alt="商品３">
                <p class="product__name">マイク</p>
            </div>
            <div class="product__item">
                <img src="{{ asset('images/HDD.jpg') }}" alt="商品４">
                <p class="product__name">HDD</p>
            </div>
            <div class="product__item">
                <img src="{{ asset('images/bag.jpg') }}" alt="商品５">
                <p class="product__name">バッグ</p>
            </div>
            <div class="product__item">
                <img src="{{ asset('images/shoes.jpg') }}" alt="商品６">
                <p class="product__name">靴</p>
            </div>
        </div>
    </div>
</div>

@endsection