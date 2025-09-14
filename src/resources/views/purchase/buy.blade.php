@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/buy.css') }}">
@endsection

@section('content')
<div class="buy-form__inner">
    <div class="item-info">
        {{-- 商品情報 --}}
        <div class="item-info__detail">
            <img class="item-info__image" src="{{ asset('storage/images/items/' . $product->image) }}" alt="{{ $product->name }}">
            <div class="item-info__detail-group">
                <div class="item__name">{{ $product->name }}</div>
                <div class="item__price">
                    &yen;<span class="price-span">{{ number_format($product->price) }}</span>
                </div>
            </div>
        </div>

        <hr>

        {{-- 支払い方法 --}}
        <div class="item-info__payment">
            <p class="payment-method__title">支払い方法</p>
            <form class="payment-method__form" action="{{ route('purchase', ['product_id' => $product->id]) }}" method="get">
                <select class="payment-method" name="payment-method" onchange="this.form.submit()">
                    <option value="" disabled selected>選択してください</option>
                    @foreach ($paymentOptions as $key => $label)
                        <option class="payment-method__option" value="{{ $key }}" {{ (string)$paymentMethod === (string)$key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                {{-- 一時的に変更したプロフィール情報を hidden input に埋め込む --}}
                <input type="hidden" name="zipcode" value="{{ old('zipcode', $profile->zipcode) }}">
                <input type="hidden" name="address" value="{{ old('address', $profile->address) }}">
                <input type="hidden" name="building" value="{{ old('building', $profile->building) }}">
            </form>
        </div>

        <hr>

        {{-- 発送先 --}}
        <div class="item-info__send">
            <div class="send__title-group">
                <div class="send__title">
                    <p>配送先</p>
                </div>
                <div class="modify">
                    <a class="modify__link" href="{{ route('purchase.address', ['product_id' => $product->id, 'paymentMethod' => $paymentMethod]) }}">変更する</a>
                </div>
            </div>
            <div class="send-address">
                <div class="zipcode">
                    〒 {{ $profile->zipcode }}
                </div>
                <div class="address">
                    {{ $profile->address }}
                </div>
                <div class="building">
                    @if ($profile->building)
                        {{ $profile->building }}
                    @endif
                </div>
            </div>
        </div>
        <hr>
    </div>


    <div class="buy-info">
        <div class="buy-info-box">
            <div class="box__row">
                <span class="box__row-price">商品代金</span>
                <span class="price-span">&yen;{{ number_format($product->price) }}</span>
            </div>
            <div class="box__row">
                <span class="box__row-method">支払い方法</span>
                <span class="payment-method-span">
                    {{ $paymentMethod && isset($paymentOptions[$paymentMethod]) 
                        ? $paymentOptions[$paymentMethod] 
                        : '未選択' }}
                </span>
            </div>
        </div>
        <form class="form-button" action="/purchase/{{ $product->id }}" method="post">
        {{-- <form class="form-button" action="{{ route('checkout.create') }}" method="post"> --}}
        @csrf
            @if ($paymentMethod)
                <input type="hidden" name="paymentMethod" value="{{ $paymentMethod }}">
                <input type="hidden" name="zipcode" value="{{ $profile->zipcode }}">
                <input type="hidden" name="address" value="{{ $profile->address }}">
                <input type="hidden" name="building" value="{{ $profile->building }}">
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <button class="buy-form__button">購入する</button>
            @else
                <button class="buy-form__button disabled" disabled>購入する</button>
            @endif
        </form>
    </div>
</div>
@endsection 