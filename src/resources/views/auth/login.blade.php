@extends('layouts.only_logo')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<h2 class="content__heading">ログイン</h2>
<div class="login-form__inner">
    <form class="login-form__form" action="/login" method="post">
        @csrf
        <div class="login-form__group">
            <label class="login-form__label" for="email">メールアドレス</label>
            <input class="login-form__input" type="text" name="email" value="{{ old('email') }}">
            <p class="login-form__error-message">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="login-form__group">
            <label class="login-form__label" for="password">パスワード</label>
            <input class="login-form__input" type="password" name="password">
            <p class="login-form__error-message">
                @error('password')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <button class="login-form__button" type="submit">ログインする</button>
        <div class="login-form__link">
            <a class="login-form__link-register" href="{{ url('/register') }}">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection