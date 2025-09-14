<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /* 購入した商品と商品テーブルのリレーション */
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
