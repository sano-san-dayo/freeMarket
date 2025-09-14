@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<h2 class="content__heading">商品の出品</h2>
<div class="sell-form__inner">
    <form class="sell-form__image" id="item-image__upload" action="/sell/upload" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-form__detail">
            <label class="sell-form__label">商品画像</label>
            <div class="item__image">
                @if ($fileName)
                    <img class="preview-image" src="{{ asset('storage/images/items/' . $fileName) }}" alt="商品">
                @endif
                <label class="item-image__button" for="image-file">画像を選択する</label>
                <input class="hidden-input" type="file" name="image-file" id="image-file" onchange="document.getElementById('item-image__upload').submit()">
                <p class="sell-form__error-message">
                    @error('image-file')
                    {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
    </form>
    <!-- <div class="sel-form__image"> -->
        <!-- <label class="sell-form__label">商品画像</label> -->
        <!-- @if ($fileName)
            <img class="user__img" src="{{ asset('storage/images/items/' . $fileName) }}" alt="商品">
        @endif -->
        <!-- <form class="item-form__image" id="item-image__upload" action="/sell/upload" method="post" enctype="multipart/form-data">
            @csrf
            <label class="item-image__button" for="image-file">画像を選択する</label>
            <input class="hidden-input" type="file" name="image-file" id="image-file" onchange="document.getElementById('item-image__upload').submit()">
            <p class="sell-form__error-message">
                @error('image-file')
                {{ $message }}
                @enderror
            </p>
        </form> -->
    <!-- </div> -->
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
                </div>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">商品の状態</label>
                <select class="item-condition" name="condition">
                    <option value="item-condition" disabled selected>選択してください</option>
                    @foreach ($conditions as $key => $label)
                        <option class="item-condition__option" value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="sell-form__info">
            <label class="sell-form__title">商品名と説明</label>
            <hr>
            <div class="sell-form__group">
                <label class="sell-form__label">商品名</label>
                <input class="sell-form__input" type="text" name="name">
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">ブランド名</label>
                <input class="sell-form__input" type="text" name="brand">
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">商品の説明</label>
                <textarea name="description" cols="50" rows="10"></textarea>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label">販売価格</label>
                <div class="price__input">
                    <input class="sell-form__input" type="text" name="price">
                </div>
            </div>
        </div>
        <input type="hidden" name="image" value="{{ $fileName }}">
        <button class="sell-button">出品する</button>
    </form>
</div>
@endsection 