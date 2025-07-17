@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<h2 class="content__heading">ログイン</h2>
<div class="login-form__inner">
    <form class="login-form__form" action="/login" method="post">
        @csrf
        <div class="login-form__group">
            <label class="login-form__label" for="name">ユーザー名</label>
            <input class="login-form__input" type="text" name="name">
            <!-- ToDo: バリデーション作成後に追加 -->
        </div>
        <div class="login-form__group">
            <label class="login-form__label" for="password">パスワード</label>
            <input class="login-form__input" type="password" name="password">
            <!-- ToDo: バリデーション作成後に追加 -->
        </div>
        <button class="register-form__button" type="submit">登録する</button>
        <a href="{{ url('/register') }}">ユーザ登録はこちら</a>
    </form>
</div>
@endsection