@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase-address.css') }}">
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

<div class="address__content">
    <div class="address__heading">
        <h2>住所の変更</h2>
    </div>
    <form action="" method="post" class="address__form">
        @csrf
        <div class="address__group">
            <label for="postal_code" class="address__group-label">郵便番号</label>
            <div class="address__group-input">
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
            </div>
            <div class="address__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="address__group">
            <label for="address" class="address__group-label">住所</label>
            <div class="address__group-input">
                <input type="text" name="address" id="address" value="{{ old('address') }}">
            </div>
            <div class="address__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="address__group">
            <label for="building" class="address__group-label">建物名</label>
            <div class="address__group-input">
                <input type="text" name="building" id="building" value="{{ old('building') }}">
            </div>
            <div class="address__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="address__button">
            <button class="address__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection