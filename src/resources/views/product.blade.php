@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<h2 class="content__heading">商品一覧</h2>
<div class="product-form__inner">
    <div class="product-form__tab">
        <!-- <label><input class="tab__input" type="radio" name="tab"selected>おすすめ</label>
        <label><input class="tab__input" type="radio" name="tab">マイリスト</label> -->
        <a href="{{ route('index', ['tab' => 'recommend']) }}" class="{{ $tab === 'recommend' ? 'active' :   '' }}">おすすめ</a>
        <a href="{{ route('index', ['tab' => 'mylist']) }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="product-form__items">
        @forelse ($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}">
                <div class="product-name">{{ $product->name }}</div>
            </div>
        @empty  
        @endforelse
    </div>
</div>

@endsection 