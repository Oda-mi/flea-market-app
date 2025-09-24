@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')


<div class="auth__content">
    <div class="auth__heading">
        <h2>ログイン</h2>
    </div>
    <form action="" method="post" class="auth__form">
        @csrf
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
        <div class="form__button">
            <button class="form__button-submit" type="submit">ログインする</button>
        </div>
        <div class="auth__link">
            <a href="" class="link-button">会員登録はこちら</a>
        </div>
    </form>
</div>

@endsection