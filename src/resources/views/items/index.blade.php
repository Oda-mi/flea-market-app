@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
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
        @foreach ($items as $item)
        <div class="product__item">
            <a href="{{ route('items.show' , $item->id) }}" class="show-link">
                <img src="{{ asset('images/' . $item->img_url) }}" alt="{{ $item->name }}">
                <p class="product__name">{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>

@endsection
