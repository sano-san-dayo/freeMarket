<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /* いいね とのリレーション */
    public function likes() {
        return $this->hasMany(Likes::class);
    }

    /* コメント とのリレーション */
    public function comments() {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');;
    }

    /* 出品者 とのリレーション */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /* カテゴリー とのリレーション */
    public function categories() {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }
}
