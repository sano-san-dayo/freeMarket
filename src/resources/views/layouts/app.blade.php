<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>freeMarket</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <div class="app">
        <header class="header">
            <div class="header__inner">
                <div class="header__logo">
                    <img class="logo" src="{{ asset('images/logo.svg') }}" alt="ロゴ" onclick="location.href='/'">
                </div>
                <div class="header__search">
                    <form action="/" method="get">
                        @csrf
                        <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
                        <input class="search-box" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか?" onkeydown="if(event.key === 'Enter'){ this.form.submit }">
                    </form>
                </div>
                <div class="header__button">
                    @auth
                        <form class="header__logout" action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="logout-button__submit" type="submit">ログアウト</button>
                        </form>
                    @endauth
                    @guest
                        <a class="login_link" href="{{ route('login') }}">ログイン</a>
                    @endguest
                    <a class="header__mypage" href="{{ route('mypage') }}">マイページ</a>
                    <a class="header__list" href="{{ route('sell') }}">出品</a>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>