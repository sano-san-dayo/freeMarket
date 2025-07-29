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
                    <img src="{{ asset('images/logo.svg') }}">
                </div>
                <div class="search-box">
                    <input type="text" placeholder="なにをお探しですか?">
                </div>
                <nav class="header__nav">
                    <form class="header-form__logout" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="header-form__button" type="submit">ログアウト</button>
                    </form>
                    <a class="header__mypage" href="#">マイページ</a>
                    <a class="header__burton" href="#">出品</a>
                </nav>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>