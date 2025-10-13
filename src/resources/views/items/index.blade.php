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

<div class="product">
    <div class="product__list">
        @if ($tab === 'myList' && !auth()->check())
        <p class="product__message">マイリストを表示するにはログインしてください</p>
        @elseif ($tab === 'myList' && auth()->check())
            @if ($items->isEmpty())
            <p class="product__message">お気に入りに追加した商品はありません</p>
            @else
                @foreach ($items as $item)
                <div class="product__item">
                    <a href="{{ route('items.show' , $item->id) }}" class="product__link">
                        <img src="{{ asset('storage/images/' . $item->img_url) }}" alt="{{ $item->name }}">
                        <p class="product__name">{{ $item->name }}</p>
                    </a>
                    @if ($item->is_sold)
                    <p class="product__sold">Sold</p>
                    @endif
                </div>
                @endforeach
            @endif
        @else
            @forelse ($items as $item)
            <div class="product__item">
                <a href="{{ route('items.show' , $item->id) }}" class="product__link">
                    <img src="{{ asset('storage/images/' . $item->img_url) }}" alt="{{ $item->name }}">
                    <p class="product__name">{{ $item->name }}</p>
                </a>
                @if ($item->is_sold)
                <p class="product__sold">Sold</p>
                @endif
            </div>
            @empty
            <p class="product__message">商品が見つかりませんでした</p>
            @endforelse
        @endif
    </div>
</div>

@endsection
