@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')

<div class="profile-edit">
    <div class="profile-edit__heading">
        <h2>プロフィール設定</h2>
    </div>
        <form action="{{ route('mypage.update') }}" method="post" enctype="multipart/form-data" class="profile-edit__form">
        @csrf
        @method('PUT')

        <div class="profile-edit__image">
            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('images/default.png') }}" id="profilePreview" alt="プロフィール画像" class="profile-edit__image-img">
            <input type="file" name="profile_image" id="profile_image" class="profile-edit__image-input">
            <label for="profile_image" class="profile-edit__image-btn">画像を選択する</label>
        </div>

        <div class="profile-edit__field">
            <label for="name"  class="profile-edit__label">ユーザー名</label>
            <div class="profile-edit__input">
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            </div>
            <div class="profile-edit__error">
            @error('name')
            {{ $message }}
            @enderror
            </div>
        </div>

        <div class="profile-edit__field">
            <label for="postal_code" class="profile-edit__label">郵便番号</label>
            <div class="profile-edit__input">
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            </div>
            <div class="profile-edit__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile-edit__field">
            <label for="address" class="profile-edit__label">住所</label>
            <div class="profile-edit__input">
                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
            </div>
            <div class="profile-edit__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile-edit__field">
            <label for="building" class="profile-edit__label">建物名</label>
            <div class="profile-edit__input">
                <input type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
            </div>
            <div class="profile-edit__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile-edit__button">
            <button class="profile-edit__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('profile_image');
        const preview = document.getElementById('profilePreview');

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(loadEvent) {
                    preview.src = loadEvent.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>

@endpush