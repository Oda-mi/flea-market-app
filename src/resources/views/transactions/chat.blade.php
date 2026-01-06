@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transactions/chat.css') }}">
@endsection

@section('content')

<div class="transaction">
    <div class="transaction__container">

        <aside class="transaction__aside">
            <h1 class="transaction__aside-title">その他の取引</h1>
            <ul class="transaction__list">
                @foreach($otherTransactions as $otherTransaction)
                    <li class="transaction__list-item {{ $transaction->id === $otherTransaction->id ? 'active' : '' }}">
                        <a href="{{ route('transactions.show', ['id' => $otherTransaction->id]) }}">
                            {{ $otherTransaction->item->name }}</a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="transaction__main">
            <div class="transaction__user">
                <div class="transaction__user-image">
                    <img src="{{ $transaction->buyer_id === auth()->id()
                                ? ($transaction->seller->profile_image ? asset('storage/'.$transaction->seller->profile_image) : asset('/images/default.png'))
                                : ($transaction->buyer->profile_image ? asset('storage/'.$transaction->buyer->profile_image) : asset('/images/default.png'))
                            }}"
                            alt="プロフィール画面"
                            class="transaction__user-image-img">
                </div>
                <div class="transaction__user-name">
                    <p class="transaction__user-name-text">
                        {{ $transaction->buyer_id === auth()->id()
                            ? $transaction->seller->name
                            : $transaction->buyer->name }}
                            さんとの取引画面
                    </p>
                </div>
                <div class="transaction__complete-button">
                    <button class="complete-button">取引を完了する</button>
                </div>
            </div>

        <div class="transaction__divider"></div>

            <div class="transaction__item-info">
                <div class="transaction__item-img">
                    <img src="{{ asset('storage/images/' . $transaction->item->img_url) }}" alt="{{ $transaction->item->name }}">
                </div>
                <div class="transaction__item-detail">
                    <p class="transaction__item-name">{{ $transaction->item->name }}</p>
                    <p class="transaction__item-price">&yen;<span>{{ number_format($transaction->item->price) }}</span></p>
                </div>
            </div>

        <div class="transaction__divider"></div>

            <div class="transaction__chat-area">

                @foreach($transaction->messages as $message)
                    <div class="transaction__chat-message {{ $message->user_id === auth()->id() ? 'transaction__chat-message--me' : 'transaction__chat-message--other' }}">
                        <div class="chat-user-info">
                            <img src="{{ $message->user->profile_image ? asset('storage/'.$message->user->profile_image) : asset('/images/default.png') }}" alt="プロフィール画面" class="chat-user-icon">
                            <p class="chat-user-name">{{ $message->user->name }}</p>
                    </div>
                    <div class="chat-bubble">
                        <p>{{ $message->message }}</p>
                    </div>
                                        <div class="chat-actions">
                        <button class="edit-button">編集</button>
                        <button class="delete-button">削除</button>
                    </div>
                </div>
                @endforeach
            </div>

            <form action="{{ route('transactions.storeMessage', $transaction->id) }}" method="post">
                @csrf
                <div class="form_error">
                    @error('message')
                        {{ $message }}
                    @enderror
                </div>
                <div class="chat-input-area">
                    <textarea
                        name="message"
                        class="chat-textarea"
                        placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                    <button type="button" class="image-button">画像を追加</button>
                    <button type="submit" class="send-button">
                        <img src="{{ asset('images/send-icon.png') }}" alt="送信ボタン" class="send-icon-img">
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- 取引完了・評価モーダルウィンドウ--}}
<div class="modal modal--hidden" id="ratingModal">
    <div class="modal__content">
        <p class="modal__title">
            取引が完了しました。
        </p>

        <div class="modal__divider"></div>

        <p class="modal__text">
            今回の取引相手はどうでしたか？
        </p>

        <div class="modal__rating">
            <span class="star star--inactive">★</span>
            <span class="star star--inactive">★</span>
            <span class="star star--inactive">★</span>
            <span class="star star--inactive">★</span>
            <span class="star star--inactive">★</span>
        </div>

        <div class="modal__divider"></div>

        <div class="modal__button">
            <button class="modal__button-submit">
                送信する
            </button>
        </div>
    </div>
</div>

@endsection