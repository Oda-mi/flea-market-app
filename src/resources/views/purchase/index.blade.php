@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/index.css') }}">
@endsection

@section('content')

<div class="purchase">
    {{--左側カラム--}}
    <div class="purchase__left">
        <div class="purchase__item">
            <img src="{{ asset('storage/images/' . $item->img_url) }}" alt="{{ $item->name }}">
            <div class="purchase__item-info">
                <p class="purchase__item-name">{{ $item->name }}</p>
                <p class="purchase__item-price">&yen;<span>{{ number_format($item->price) }}</span></p>
            </div>
        </div>

        <form action="{{ route('purchase.store' , $item->id) }}" method="post">
            @csrf
        <div class="purchase__payment">
            <h3>支払い方法</h3>
            <select name="payment_method" id="payment_method" class="purchase__payment-select">
                <option value="" disabled selected>選択してください</option>
                <option value="convenience" {{ old('payment_method') == 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                <option value="credit" {{ old('payment_method') == 'credit' ? 'selected' : '' }}>カード支払い</option>
            </select>
            <div class="purchase__error">
                @error('payment_method')
                {{ $message }}
                @enderror
            </div>
        </div>

        <input type="hidden" name="postal_code" value="{{ $user->postal_code }}">
        <input type="hidden" name="address" value="{{ $user->address }}">
        <input type="hidden" name="building" value="{{ $user->building }}">
        <div class="purchase__address">
            <div class="purchase__address-header">
                <h3>配送先</h3>
                <a href="{{ route('purchase.address' ,$item->id) }}" class="btn-change-address">変更する</a>
            </div>
            <div class="purchase__address-body">
                <p>〒{{ $user->postal_code }}</p>
                <p>{{ $user->address }}</p>
                <p>{{ $user->building }}</p>
            </div>
            <div class="purchase__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
            <div class="purchase__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>

    {{--右側カラム--}}
    <div class="purchase__right">
        <div class="purchase__summary">
            <p>商品代金</p>
            <p class="purchase__summary-price">&yen;<span>{{ number_format($item->price) }}</span></p>
        </div>
        <div class="purchase__summary">
            <p>支払方法</p>
            <p class="purchase__summary-method" id="summary-method"></p>
        </div>

        {{--プルダウンで選択した支払い方法を反映させる--}}
        <script>
        document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_method');
        const summaryMethod = document.getElementById('summary-method');

        paymentSelect.addEventListener('change', function() {
            const selectedPaymentText = paymentSelect.options[paymentSelect.selectedIndex].text;
            summaryMethod.textContent = selectedPaymentText;
        });
        });
        </script>

        <button class="purchase__button">購入する</button>
        </form>
    </div>
</div>

@endsection

