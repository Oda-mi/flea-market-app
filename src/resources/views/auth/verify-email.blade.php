@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')

<div class="verify-email__content">
    <p>
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <div class="verify-email__form">
        <form action="" method="get">
            <div class="verify-email__button">
                <button type="submit">認証はこちらから</button>
            </div>
        </form>
        <div class="verify-email__link">
            <a href="">認証メールを再送する</a>
        </div>
    </div>
</div>

@endsection