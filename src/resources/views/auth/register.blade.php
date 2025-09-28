@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')


<div class="auth__content">
    <div class="auth__heading">
        <h2>会員登録</h2>
    </div>
    <form action="{{ route('register') }}" method="post" class="auth__form" novalidate>
        @csrf
        <div class="form__group">
            <label for="name" class="form__group-label">ユーザー名</label>
            <div class="form__group-input">
                <input type="text" name="name" id="name" value="{{ old('name') }}" >
            </div>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        <div class="form__group">
            <label for="email" class="form__group-label">メールアドレス</label>
            <div class="form__group-input">
                <input type="email" name="email" id="email" value="{{ old('email') }}" >
            </div>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <label for="password" class="form__group-label">パスワード</label>
            <div class="form__group-input">
                <input type="password" name="password" id="password">
            </div>
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <label for="password" class="form__group-label">確認用パスワード</label>
            <div class="form__group-input">
                {{--確認用パスワードname/idは"password_confirmation"がLaravelの標準--}}
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録する</button>
        </div>
        <div class="auth__link">
            <a href="{{ route('login') }}" class="link-button">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection