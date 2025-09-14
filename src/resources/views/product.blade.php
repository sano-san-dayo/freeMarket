@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<div class="product-form__inner">
    <div class="product-form__tab">
        <a href="{{ route('index', ['tab' => 'recommend', 'keyword' => $keyword]) }}" class="{{ $tab === 'recommend' ? 'active' :   '' }}">おすすめ</a>
        <a href="{{ route('index', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <hr>
    
    <div class="product-form__items">
        @foreach ($products as $product)
            <div class="product-card" onclick="location.href='/items/{{ $product->id }}'">
                <div class="product-image">
                    <img src="{{ asset('storage/images/items/' . $product->image) }}" alt="{{ $product->name }}">
                    @foreach ($purchases as $purchase)
                        @if ($product->id === $purchase->product_id)
                            <div class="product-card__sold">sold</div>
                        @endif
                    @endforeach
                </div>
                <div class="product-name">
                    {{ $product->name }}
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection 