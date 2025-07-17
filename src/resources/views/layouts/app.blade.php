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
            <div header-logo>
                <img class="header-logo" src="{{ asset('images/logo.svg') }}">
                @yield('link')
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>