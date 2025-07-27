@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<h2 class="content__heading">プロフィール設定</h2>
<div class="profile-form__inner">
    <form class="profile-image__form" action="" method="post">
        @csrf
        <button class="profile-image__button">画像を選択する</button>
    </form>
    <form class="profile-form__form" action="" method="post">
        @csrf
        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input class="profile-form__input" type="text" name="name" value="{{ old('name') }}">
            <p class="profile-form__error-message">
                @error('name')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="zipcode">郵便番号</label>
            <input class="profile-form__input" type="text" name="zipcode" value="{{ old('zipcode') }}">
            <p class="profile-form__error-message">
                @error('zipcode')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input class="profile-form__input" type="text" name="address" value="{{ old('address') }}">
            <p class="profile-form__error-message">
                @error('address')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input class="profile-form__input" type="text" name="building" value="{{ old('building') }}">
            <p class="profile-form__error-message">
                @error('building')
                {{ $message }}
                @enderror
            </p>
        </div>
        <button class="profile-form__button" type="submit">更新する</button>
    </form>
</div>
@endsection