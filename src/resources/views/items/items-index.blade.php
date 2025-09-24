@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/items-index.css') }}">
@endsection

@section('content')

<div class="nav">
    <div class="nav__tabs">
    <a href="" class="nav__tab nav__tab--active">おすすめ</a>
    <a href="" class="nav__tab">マイリスト</a>
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
    </div>
</div>

@endsection