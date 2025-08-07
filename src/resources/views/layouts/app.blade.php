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
                    <img class="logo" src="{{ asset('images/logo.svg') }}" alt="ロゴ">
                </div>
                <div class="header__search">
                    <input class="search-box" type="text" placeholder="なにをお探しですか?">
                </div>
                <div class="header__button">
                    <form class="header__logout" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="logout-button__submit" type="submit">ログアウト</button>
                    </form>
                    <a class="header__mypage" href="#">マイページ</a>
                    <a class="header__list" href="#">出品</a>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>