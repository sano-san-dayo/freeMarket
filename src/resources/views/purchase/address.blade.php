@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<h2 class="content__heading">住所の変更</h2>
<div class="address-form__inner">
    <form class="address-form__form" action="/purchase/address" method="post">
        @csrf
        <div class="address-form__group">
            <label class="address-form__label" for="text">郵便番号</label>
            <input class="address-form__input" type="text" name="zipcode">
            <p class="address-form__error-message">
                @error('zipcode')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="text">住所</label>
            <input class="address-form__input" type="text" name="address">
            <p class="address-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="text">建物名</label>
            <input class="address-form__input" type="text" name="building">
            <p class="address-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <input type="hidden" name="product_id" value="{{ $product_id }}">
        <input type="hidden" name="paymentMethod" value="{{ $paymentMethod }}">

        <button class="address-form__button" type="submit">
            更新する
        </button>
    </form>
</div>
@endsection