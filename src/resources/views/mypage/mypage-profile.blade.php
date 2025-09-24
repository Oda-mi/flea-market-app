@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/mypage-profile.css') }}">
@endsection

@section('content')

<div class="profile-edit__content">  {{--プロフィール編集--}}
    <div class="profile-edit__heading">
        <h2>プロフィール設定</h2>
    </div>
    {{--enctype="multipart/form-data"画像送信に必須--}}
    <form action="" method="post" enctype="multipart/form-data" class="profile-edit__form">
        @csrf
        @method('PUT')  {{--PUT 更新メソッド（上書き）--}}

        <div class="profile-edit__image">
            <img src="{{ $user->profile_image ?? 'default.png' }}" alt="プロフィール画像" class="image">
            <input type="file" name="profile_image" id="profile_image" class="image-input">
            <label for="profile_image" class="image-btn">画像を選択する</label>
        </div>

        <div class="profile__group">
            <label for="name"  class="profile__group-label">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>
        <div class="profile__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>

        <div class="profile__group">
            <label for="postal_code" class="profile__group-label">郵便番号</label>
            <div class="profile__group-input">
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
            </div>
            <div class="profile__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile__group">
            <label for="address" class="profile__group-label">住所</label>
            <div class="profile__group-input">
                <input type="text" name="address" id="address" value="{{ old('address') }}">
            </div>
            <div class="profile__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile__group">
            <label for="building" class="profile__group-label">建物名</label>
            <div class="profile__group-input">
                <input type="text" name="building" id="building" value="{{ old('building') }}">
            </div>
            <div class="profile__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile__button">
            <button class="profile__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection