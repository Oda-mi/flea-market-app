<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitaize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
<header class="header">
    <div class="header__inner">
        <a href="/" class="header__logo">
            <img src="{{ asset('images/logo.svg') }}" alt="CoachTechLogo">
        </a>
        <div class="header__search">
            <form action="{{ route('items.index') }}" method="GET" class="search-form">
                @csrf
                <input type="text" name="keyword" value="{{ request('keyword') }}"class="search-form__input" placeholder="なにをお探しですか？">
                <input type="hidden" name="page" value="{{ $tab ?? request('page', 'recs') }}">
            </form>
        </div>
        <div class="header__buttons">
            @guest
            <a href="{{ route('login') }}" class="button button--login">ログイン</a>
            <a href="{{ route('mypage.index') }}" class="button button--mypage">マイページ</a>
            <a href="{{ route('items.sell') }}" class="button button--sell">出品</a>
            @endguest

            @auth
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="button button--logout">ログアウト</button>
            </form>
            <a href="{{ route('mypage.index') }}" class="button button--mypage">マイページ</a>
            <a href="{{ route('items.sell') }}" class="button button--sell">出品</a>
            @endauth
        </div>
    </div>
</header>

    <main>
        @yield('content')
    </main>
</body>
</html>