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
                    <div class="transaction__chat-message {{ $message->user_id === auth()->id()
                                ? 'transaction__chat-message--me'
                                : 'transaction__chat-message--other' }}">
                        <div class="transaction__chat-user-info">
                            <img src="{{ $message->user->profile_image
                                    ? asset('storage/'.$message->user->profile_image)
                                    : asset('/images/default.png') }}"
                                    alt="プロフィール画面"
                                    class="transaction__chat-user-icon">
                            <p class="transaction__chat-user-name">{{ $message->user->name }}</p>
                        </div>

                        @if($message->image_path)
                            <div class="transaction__chat-bubble-image">
                                <img src="{{ asset('storage/' . $message->image_path) }}" alt="送信画像" class="transaction__chat-bubble-image-img">
                            </div>
                        @endif

                        <div class="transaction__chat-bubble-text">
                            <p class="transaction__chat-bubble-text-message">
                                {!! nl2br(e($message->message)) !!}
                            </p>

                            <form action="{{ route('transactions.updateMessages', $message->id) }}"
                                        id="edit-form-{{ $message->id }}"
                                        method="post"
                                        class="edit-form hidden">
                                @csrf
                                @method('patch')
                                <textarea name="message" class="edit-textarea">{{ $message->message }}</textarea>
                            </form>
                        </div>

                        @if ($message->user_id === auth()->id())
                            <div class="chat-actions">
                                {{-- 通常時 --}}
                                <button class="edit-button">編集</button>

                                <form action="{{ route('transactions.destroyMessages', $message->id) }}" method="post" class="delete-form">
                                    @csrf
                                    @method('delete')
                                    <button
                                        class="delete-button"
                                        onclick="return confirm('このメッセージを削除しますか？')">
                                        削除
                                    </button>
                                </form>

                                {{-- 編集時 --}}
                                <button
                                    type="submit"
                                    class="save-button hidden"
                                    form="edit-form-{{ $message->id }}">
                                    保存
                                </button>

                                <button
                                    type="button"
                                    class="cancel-button hidden">
                                    キャンセル
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <form action="{{ route('transactions.storeMessages', $transaction->id) }}"
                    method="post"
                    class="chat-send-form"
                    enctype="multipart/form-data">
                @csrf
                <div class="form_error">
                    @error('message')
                        {{ $message }}
                    @enderror
                    @error('image')
                        {{ $message }}
                    @enderror
                </div>
                <div class="chat-input-area">
                    <textarea
                        name="message"
                        id = "chat-message"
                        class="chat-textarea"
                        placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                    <div class="image-button">
                        <label for="chat-image-input" class="image-button-label">画像を追加</label>
                        <input type="file" name="image" id="chat-image-input" class="chat__image-input">
                    </div>
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

@push('scripts')

{{-- 入力情報保持機能 と チャット編集切り替え--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        //入力情報保持機能
        const textarea = document.getElementById('chat-message');
        const storageKey = 'draft_message_user_{{ auth()->id() }}_transaction_{{ $transaction->id }}';

        // ページ読み込み時に入力復元
        textarea.value = localStorage.getItem(storageKey) || '';

        // 入力するたびに localStorage に保存
        textarea.addEventListener('input', () => {
            localStorage.setItem(storageKey, textarea.value);
        });

        // 送信時に localStorage から削除
        const sendForm = document.querySelector('.chat-send-form');

        sendForm.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });



        //チャット編集切り替え
        //編集ボタン押下時
        document.querySelectorAll('.edit-button').forEach(editButton => {
            editButton.addEventListener('click', () => {

                const chatActions = editButton.closest('.chat-actions');
                const chatBubble  = chatActions.previousElementSibling;

                const messageText      = chatBubble.querySelector('.transaction__chat-bubble-text-message');
                const editForm         = chatBubble.querySelector('.edit-form');
                const editTextarea     = editForm.querySelector('.edit-textarea');

                const saveButton       = chatActions.querySelector('.save-button');
                const cancelButton     = chatActions.querySelector('.cancel-button');
                const deleteForm       = chatActions.querySelector('.delete-form');

                // 表示切り替え
                messageText.classList.add('hidden');
                editForm.classList.remove('hidden');

                editButton.classList.add('hidden');
                deleteForm.classList.add('hidden');

                saveButton.classList.remove('hidden');
                cancelButton.classList.remove('hidden');

                editTextarea.focus();
            });
        });

        //キャンセルボタン押下時
        document.querySelectorAll('.cancel-button').forEach(cancelButton => {
            cancelButton.addEventListener('click', () => {

                const chatActions = cancelButton.closest('.chat-actions');
                const chatBubble  = chatActions.previousElementSibling;

                const messageText  = chatBubble.querySelector('.transaction__chat-bubble-text-message');
                const editForm     = chatBubble.querySelector('.edit-form');
                const editTextarea = editForm.querySelector('.edit-textarea');

                const editButton   = chatActions.querySelector('.edit-button');
                const saveButton   = chatActions.querySelector('.save-button');
                const deleteForm   = chatActions.querySelector('.delete-form');

                // textareaを元の本文に戻す
                editTextarea.value = messageText.textContent.trim();

                // 表示切り替え
                messageText.classList.remove('hidden');
                editForm.classList.add('hidden');

                editButton.classList.remove('hidden');
                deleteForm.classList.remove('hidden');

                saveButton.classList.add('hidden');
                cancelButton.classList.add('hidden');
            });
        });
    });
</script>

@endpush
