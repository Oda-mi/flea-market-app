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
                <li class="transaction__list-item">
                    <a href="">商品名</a>
                </li>
                <li class="transaction__list-item">
                    <a href="">商品名</a>
                </li>
                <li class="transaction__list-item">
                    <a href="">商品名</a>
                </li>
            </ul>
        </aside>

        <div class="transaction__main">
            <div class="transaction__user">
                <div class="transaction__user-image">
                    <img src="{{ asset('images/onion.jpg') }}" alt="プロフィール画面" class="transaction__user-image-img">
                </div>
                <div class="transaction__user-name">
                    <p class="transaction__user-name-text">
                        ユーザー名さんとの取引画面
                    </p>
                </div>
                <div class="transaction__complete-button">
                    <button class="complete-button">取引を完了する</button>
                </div>
            </div>

        <div class="transaction__divider"></div>

            <div class="transaction__item-info">
                <div class="transaction__item-img">
                    <img src="{{ asset('images/bag.jpg') }}" alt="商品名">
                </div>
                <div class="transaction__item-detail">
                    <p class="transaction__item-name">商品名</p>
                    <p class="transaction__item-price">&yen;<span>5000</span></p>
                </div>
            </div>

        <div class="transaction__divider"></div>

            <div class="transaction__chat-area">

                <div class="transaction__chat-message transaction__chat-message--other">
                    <div class="chat-user-info">
                        <img src="{{ asset('images/HDD.jpg') }}" alt="プロフィール画面" class="chat-user-icon">
                        <p class="chat-user-name">相手ユーザー</p>
                    </div>
                    <div class="chat-bubble">
                        <p>こんにちは</p>
                    </div>
                </div>

                <div class="transaction__chat-message transaction__chat-message--me">
                    <div class="chat-user-info">
                        <p class="chat-user-name">自分ユーザー</p>
                        <img src="{{ asset('images/coffee.jpg') }}" alt="プロフィール画面" class="chat-user-icon">
                    </div>
                    <div class="chat-bubble">
                        <p>よろしくお願いします！</p>
                    </div>
                    <div class="chat-actions">
                        <button class="edit-button">編集</button>
                        <button class="delete-button">削除</button>
                    </div>
                </div>

            </div>

            <div class="chat-input-area">
                <textarea
                    name=""
                    id=""
                    class="chat-textarea"
                    placeholder="取引メッセージを記入してください"></textarea>
                <button class="image-button">画像を追加</button>
                <button class="send-button">
                    <img src="{{ asset('images/send-icon.png') }}" alt="送信ボタン" class="send-icon-img">
                </button>
            </div>

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