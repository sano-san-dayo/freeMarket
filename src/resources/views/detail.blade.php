@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail-form__inner">
    <div class="detail-image">
        <img src="{{ asset('storage/images/items/' . $product->image) }}" alt="{{ $product->name }}">
    </div>

    <div class="detail-info">
        button = {{ $button }}
        <div class="detail-form__buy">
            <div class="item-name">{{ $product->name }}</div>
            <div class="brand">{{ $product->brand }}</div>
            <div class="price">&yen;<span class="price-span">{{ number_format($product->price) }}</span>&nbsp;(税込)</div>
            <div class="like-comment">
                <div class="like">
                    @if ($button != 0)
                        <button class="like-button" type="submit">
                            @if ($liked === 1)
                                <img class="like-img" src="{{ asset('images/星アイコン_お気に入り.png') }}" alt="いいね">
                            @else
                                <img class="like-img" src="{{ asset('images/星アイコン.png') }}" alt="いいね">
                            @endif
                            <input type="hidden" name="liked" value="{{ $liked }}">
                        </button>
                        <nav class="count">{{ $like_count }}</nav>
                    @else
                        <form action="/like/{{ $product->id }}" method="post">
                            @csrf
                            <button class="like-button" type="submit">
                                @if ($liked === 1)
                                    <img class="like-img" src="{{ asset('images/星アイコン_お気に入り.png') }}" alt="いいね">
                                @else
                                    <img class="like-img" src="{{ asset('images/星アイコン.png') }}" alt="いいね">
                                @endif
                                <input type="hidden" name="liked" value="{{ $liked }}">
                            </button>
                            <nav class="count">{{ $like_count }}</nav>
                        </form>
                    @endif
                </div>
                <div class="comment">
                    <img class="comment-img"src="{{ asset('images/吹き出しのアイコン.png') }}" alt="コメント">
                    <nav class="count">{{ $product->comments->count() }}</nav>
                </div>
            </div>
            @if (!$purchase->isEmpty() || ($button != 0))
                <button class="detail-form__buy-button disabled" disabled>購入手続きへ</button>
            @else
                <form action="{{ route('purchase', ['product_id' => $product->id, 'profile' => null ]) }}" method="get">
                    @csrf
                    <button class="detail-form__buy-button">購入手続きへ</button>
                </form>
            @endif
        </div>
        
        <div class="detail-form__explanation">
            <h2 class="title-description">商品説明</h2>
            <div class="item-description">{{ $product->description }}</div>
        </div>

        <div class="detail-form__info">
            <h2 class="title-info">商品の情報</h2>
            <div class="category-group">
                <div class="title-category">カテゴリー</div>
                <div class="category">
                    @foreach ($categories as $category)
                        <div class="category__name">{{ $category->name }}</div>
                    @endforeach
                </div>
            </div>
            <div class="status-group">
                <div class="title-status">商品の状態</div>
                <div class="condition">{{ $condition }}</div>
            </div>
        </div>

        <div class="detail-form__comment">
            <h2 class="title-comment">コメント({{ $product->comments->count() }})</h2>
            <div class="comment-box">
                @foreach ($product->comments as $comment)
                    <div class="comment-user-group">
                        @if ($comment->user->profile->image)
                            <img class="comment-user__img" src="{{ asset('storage/images/profile/' . $comment->user->profile->image) }}" alt="ユーザ">
                        @else
                            <div class="img-circle"></div>
                        @endif
                        <nav class="comment-user__name">{{ $comment->user->name }}</nav>
                    </div>
                    <p class="comment__content">{{ $comment->content }}</p>
                @endforeach
            </div>
            <form class="comment-form" action="/items/{{ $product->id }}" method="post">
                @csrf
                <div class="title-comment-form">商品へのコメント</div>
                <div class="input-comment-group">
                    <textarea class="comment__input" name="comment" cols="50" rows="5"></textarea>
                    @if ($errors->has('comment'))
                        <div class="comment-error">
                            {{ $errors->FIRST('comment') }}
                        </div>
                    @endif
                    @if ($button === 2)
                        <button class="comment__submit disabled" disabled>コメントを送信</button>
                    @else
                        <button class="comment__submit">コメントを送信</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection 