@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/regist_profile.css') }}">
@endsection

@section('content')
<h2 class="content__heading">プロフィール設定</h2>
<div class="regist-profile-form__inner">
    <form action="/mypage/regist_profile" method="post" enctype="multipart/form-data">
        <div class="regist-profile-image">
            @if (session('fileName'))
                <img class="user__img" src="{{ asset('storage/images/profile/' . session('fileName')) }}" alt="ユーザ">
                <input type="hidden" name="fileName" value="{{ session('fileName') }}">
            @elseif ($profile && $profile->image)
                <img class="user__img" src="{{ asset('storage/images/profile/' . $profile->image) }}" alt="ユーザ">
                <input type="hidden" name="fileName" value="{{ $profile->image }}">
            @else
                <div class="img-circle"></div>
            @endif
            <div class="profile-image__form">
                @csrf
                <label class="profile-image__button" for="image-file">画像を選択する</label>
                <input class="hidden-input" type="file" name="image-file" id="image-file" onchange="this.form.submit()">
                <p class="profile-form__error-message">
                    @error('image-file')
                    {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <div class="profile-form__form">
            @csrf
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                @if ($user)
                    <input class="profile-form__input" type="text" name="userName" value="{{ old('userName', $user->name) }}">
                @else
                    <input class="profile-form__input" type="text" name="userName" value="old('userName')">
                @endif
                <p class="profile-form__error-message">
                    @error('userName')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="zipcode">郵便番号</label>
                @if ($profile)
                    <input class="profile-form__input" type="text" name="zipcode" value="{{ old('zipcode', $profile->zipcode) }}">
                @else
                    <input class="profile-form__input" type="text" name="zipcode" value="{{ old('zipcode') }}">
                @endif
                <p class="profile-form__error-message">
                    @error('zipcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                @if ($profile)
                    <input class="profile-form__input" type="text" name="address" value="{{ old('address', $profile->address) }}">
                @else
                    <input class="profile-form__input" type="text" name="address" value="{{ old('address') }}">
                @endif
                <p class="profile-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                @if ($profile && $profile->building)
                    <input class="profile-form__input" type="text" name="building" value="{{ old('building', $profile->building) }}">
                @else
                    <input class="profile-form__input" type="text" name="building" value="{{ old('building') }}">
                @endif
                <p class="profile-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input type="hidden" name="redirect_from" value="{{ $redirect_from }}">
            <button class="profile-form__button" type="submit" name="action" value="update">更新する</button>
        </div>
    </form>
</div>
@endsection