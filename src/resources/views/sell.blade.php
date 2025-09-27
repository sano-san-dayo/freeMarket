@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<h2 class="content__heading">商品の出品</h2>
<div class="sell-form__inner">
    <form class="sell-form__image" id="item-image__upload" action="/sell/upload" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-form__image-file">
            <label class="sell-form__label">商品画像</label>
            <div class="item__image">
                @if(session('fileName'))
                    <img src="{{ asset('storage/images/items/' . session('fileName')) }}" alt="商品">
                    <input type="hidden" name="previousFileName" value="{{ session('fileName') }}">
                @else
                    <label class="item-image__button" for="image-file">画像を選択する</label>
                    <input class="hidden-input" type="file" name="image-file" id="image-file" onchange="document.getElementById('item-image__upload').submit()">
                @endif
            </div>
            @if(session('fileName'))
                <div class="item__button">
                    <label class="item-image__button" for="image-file2">画像を選択する</label>
                    <input class="hidden-input" type="file" name="image-file" id="image-file2"
                        onchange="document.getElementById('item-image__upload').submit()">              
            </div>
            @endif
            @error('image-file')
                <p class="sell-form__error-message">
                    {{ $message }}
                </p>
            @enderror
            @error('image')
                <p class="sell-form__error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </form>
    <form class="sell-form__item" action="/sell" method="post">
        @csrf
        <div class="sell-form__detail">
            <label class="sell-form__title">商品の詳細</label>
            <div class="sell-form__group">
                <label class="sell-form__label">カテゴリー</label>
                <div class="category-list">
                    @foreach ($categories as $category)
                        <label class="category-item">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                    <p class="sell-form__error-message">
                        @error('categories')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">商品の状態</label>
                <select class="item-condition" name="condition">
                    <option value="item-condition" disabled selected>選択してください</option>
                    @foreach ($conditions as $condition)
                        <option class="item-condition__option" value="{{ $condition->id }}">{{ $condition->name }}</option>
                    @endforeach
                </select>
                <p class="sell-form__error-message">
                    @error('condition')
                        {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <div class="sell-form__info">
            <label class="sell-form__title">商品名と説明</label>
            <hr>
            <div class="sell-form__group">
                <label class="sell-form__label">商品名</label>
                <input class="sell-form__input" type="text" name="name">
                <p class="sell-form__error-message">
                    @error('name')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">ブランド名</label>
                <input class="sell-form__input" type="text" name="brand">
                <p class="sell-form__error-message">
                    @error('brand')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">商品の説明</label>
                <textarea class="sell-form__textarea" name="description" cols="50" rows="10"></textarea>
                <p class="sell-form__error-message">
                    @error('description')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">販売価格</label>
                <div class="price__input">
                    <input class="sell-form__input" type="text" name="price">
                </div>
                <p class="sell-form__error-message">
                    @error('price')
                        {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <input type="hidden" name="image" value="{{ $fileName }}">
        <button class="sell-button">出品する</button>
    </form>
</div>
@endsection 