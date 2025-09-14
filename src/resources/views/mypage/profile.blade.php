@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__inner">
    <div class="user-info">
        <div class="user-info__img-name">
            @if ($profile && $profile->image)
                <img class="user__img" src="{{ asset('storage/images/profile/' . $profile->image) }}" alt="ユーザ">
            @else
                <div class="img-circle"></div>
            @endif
            <div class="user-info__name">{{ $user->name }}</div>
        </div>
        <form class="edit-form" action="/mypage/profile" method="get">
            <button class="button__submit">プロフィールを編集</button>
        </form>
    </div>

    <div class="profile-form__tab">
        <a href="{{ route('mypage', ['tab' => 'sale']) }}" class="{{ $tab === 'sale' ? 'active' :   '' }}">出品した商品</a>
        <a href="{{ route('mypage', ['tab' => 'purchase']) }}" class="{{ $tab === 'purchase' ? 'active' : '' }}">購入した商品</a>
    </div>

    <hr>

    <div class="profile-form__items">
        @foreach ($products as $product)
            <div class="product-card" onclick="location.href='{{ route('item.detail', ['product_id' => $product->id, 'from' => $tab])}}'">
                <div class="product-image">
                    <img src="{{ asset('storage/images/items/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="product-name">
                    {{ $product->name }}
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection