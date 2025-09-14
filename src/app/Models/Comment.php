<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /* コメント投稿者 とのリレーション */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /* コメント対象商品 とのリレーション */
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
