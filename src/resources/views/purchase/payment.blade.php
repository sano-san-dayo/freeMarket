@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class=payment-form__inner">
    <h2>購入処理が終了しました。</h2>
    <div class="back__index">
        <a href="/">商品一覧へ戻る</a>
    </div>
</div>
@endsection