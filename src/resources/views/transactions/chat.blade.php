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
                @if (auth()->id() === $transaction->buyer_id)
                    <div class="transaction__complete-button">
                        @if ($transaction->status === 'completed')
                            <button class="complete-button complete-button--disabled" disabled>
                                取引完了済
                            </button>
                        @else
                            <button class="complete-button"
                                    data-open-modal="ratingModal">
                                    取引を完了する
                            </button>
                        @endif
                    </div>
                @endif
            </div>

        <div class="transaction__divider"></div>

        <div class="scroll-area">

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
                    @if ($transaction->status === 'completed')
                        <textarea
                            name="message"
                            id = "chat-message"
                            class="chat-textarea"
                            placeholder="この取引は完了しています"
                            disabled></textarea>
                        <div class="image-button image-button--disabled">
                            <span class="image-button-label image-button-label--disabled">画像を追加</span>
                        </div>
                        <button class="send-button send-button--disabled" disabled>
                            <img src="{{ asset('images/send-icon.png') }}" alt="送信ボタン" class="send-icon-img">
                        </button>
                    @else
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
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>


{{-- 取引完了・評価モーダルウィンドウ--}}

<form action="{{ route('evaluations.store', $transaction->id) }}" method="post">
    @csrf

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
                <span class="star star--inactive" data-value="1">★</span>
                <span class="star star--inactive" data-value="2">★</span>
                <span class="star star--inactive" data-value="3">★</span>
                <span class="star star--inactive" data-value="4">★</span>
                <span class="star star--inactive" data-value="5">★</span>
            </div>

            <input type="hidden" name="rating" id="ratingInput">

            <div class="modal__divider"></div>

            <div class="modal__button">
                <button class="modal__button-submit">
                    送信する
                </button>
            </div>
        </div>
    </div>
</form>


@endsection

@push('scripts')

{{-- 入力情報保持機能 と チャット編集切り替え と モーダルウィンドウ--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* =============================
            メッセージ入力情報保持機能
        ============================= */

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


        /* =============================
            チャット編集切り替え
        ============================= */

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


        /* =============================
            取引評価モーダルウィンドウ
        ============================= */

        // 共通：モーダル開く
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.remove('modal--hidden');
        }

        // 共通：背景クリックでモーダル閉じる
        const ratingModal = document.getElementById('ratingModal');
        if (ratingModal) {
            ratingModal.addEventListener('click', (clickEvent) => {
                if (clickEvent.target === ratingModal) {
                    ratingModal.classList.add('modal--hidden');
                }
            });
        }

        // 購入者：ボタン押下でモーダル開く
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', () => {
                openModal(button.dataset.openModal);
            });
        });

        // 出品者：条件満たしたら自動でモーダル開く
        const shouldOpenModal = @json(
            auth()->id() === $transaction->seller_id
            && $transaction->status === 'completed'
            && !$transaction->evaluations()->where('evaluator_id', auth()->id())->exists()
        );

        if (shouldOpenModal) {
            openModal('ratingModal');
        }

        // ★評価クリック処理
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(clickedStar => {
            clickedStar.addEventListener('click', () => {
                const selectedValue = Number(clickedStar.dataset.value);

                // 一度全部リセット
                stars.forEach(starElement => {
                    starElement.classList.remove('star--active');
                    starElement.classList.add('star--inactive');
                });

                // クリックされた数まで光らせる
                stars.forEach(starElement => {
                    if (Number(starElement.dataset.value) <= selectedValue) {
                        starElement.classList.remove('star--inactive');
                        starElement.classList.add('star--active');
                    }
                });

                // hidden input に保存
                ratingInput.value = selectedValue;
            });
        });

    });
</script>

@endpush
