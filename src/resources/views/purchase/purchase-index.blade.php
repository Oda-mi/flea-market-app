@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase-index.css') }}">
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

<div class="purchase">  {{--購入--}}
    {{--左側カラム--}}
    <div class="purchase__left">
        <div class="purchase__item">
            <img src="{{ asset('images/coffee.jpg') }}" alt="商品画像">
            <div class="purchase__item-info">
                <p class="purchase__item-name">商品名が入ります</p>
                <p class="purchase__item-price">¥12,345</p>
            </div>
        </div>

        <div class="purchase__payment">  {{--購入__支払い--}}
            <h3>支払い方法</h3>
            <select name="payment_method" id="" class="purchase__payment-select">  {{--支払い選択--}}
                <option value="" disabled selected>選択してください</option>
                <option value="">クレジットカード</option>
                <option value="">コンビニ支払い</option>
                <option value="">銀行振込</option>
            </select>
            <div class="purchase__error">
                @error('payment_method')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="purchase__address">
            <div class="purchase__address-header">
                <h3>配送先</h3>
                <a href="" class="btn-change-address">変更する</a>
            </div>
            <div class="purchase__address-body">
            <p>〒123-4567</p>
            <p>ここに住所が表示される</p>
            </div>
        </div>
    </div>
    {{--右側カラム--}}
    <div class="purchase__right">
        <div class="purchase__summary">  {{--購入__概要--}}
            <p>商品代金</p>
            <p class="purchase__summary-price">￥12,345</p>
        </div>
        <div class="purchase__summary">
            <p>支払方法</p>
            <p class="purchase__summary-method">支払方法表示</p>  {{--概要__方法--}}
        </div>
        <button class="purchase__button">購入する</button>
    </div>
</div>

@endsection

