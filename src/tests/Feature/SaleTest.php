<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class SaleTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 出品商品情報登録 */
    public function test_sell_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* 商品出品 */
        $response = $this->post('/sell', [
            'image' => 'テスト商品.jpg',
            'categories' => [1, 2],
            'condition' => 1,
            'name' => 'テスト商品',
            'brand' => 'マイブランド',
            'description' => 'いいものだ',
            'price' => 500,
        ]);
        $response->assertStatus(200);

        /* テーブルに保存されていることを確認 */
        $this->assertDatabaseHas('products', [
            'image' => 'テスト商品.jpg',
            'condition' => 1,
            'name' => 'テスト商品',
            'brand' => 'マイブランド',
            'description' => 'いいものだ',
            'price' => 500,
        ]);

        $product = Product::where('name', 'テスト商品')->first();
        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => 1,
        ]);
        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => 2,
        ]);
    }
}
