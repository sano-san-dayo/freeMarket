@extends('layouts.only_logo')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify_notice.css') }}">
@endsection

@section('content')
<div class="verify-notice__innser">
    <div class="verify-notice__message">登録していただいたメールアドレスに認証メールを送付しました。</div>
    <div class="verify-notice__message">メール認証を完了してください。</div>
    <div>
        <form action="http://localhost:8025" method="get">
            <button class="verify-notice__butto-verify" type="submit">認証はこちらから</button>
        </form>
    </div>
    <div>
        <form class="vefir-notice-form__resend" action="/resend" method="post">
            @csrf
            <button class="verify-notice__button-resend" type="submit">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection