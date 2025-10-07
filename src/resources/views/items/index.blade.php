@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<div class="nav">
    <div class="nav__tabs">
    <a href="{{ route('items.index' , ['page'=>'recs']) }}" class="nav__tab {{ $tab === 'recs' ? 'nav__tab--active':'' }}">おすすめ</a>
    <a href="{{ route('items.index' , ['page'=>'myList']) }}" class="nav__tab {{ $tab === 'myList' ? 'nav__tab--active':'' }}">マイリスト</a>
    </div>
</div>

<div class="product">  {{--商品--}}
    <div class="product__list">
        @forelse ($items as $item)
        <div class="product__item">
            <a href="{{ route('items.show' , $item->id) }}" class="show-link">
                <img src="{{ asset('images/' . $item->img_url) }}" alt="{{ $item->name }}">
                <p class="product__name">{{ $item->name }}</p>
            </a>
            @if ($item->is_sold)
            <p class="product__sold">sold</p>
            @endif
        </div>
        @empty
        <p>商品が見つかりませんでした</p>
        @endforelse
    </div>
</div>

@endsection
