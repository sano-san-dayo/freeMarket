<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;

class SearchTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 商品名で部分一致検索 */
    public function test_search_01() {
        /* ユーザ作成 */
        $user = User::factory()->create();

        /* 商品を作成 */
        $product1 = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '未購入商品',
        ]);
        $product2 = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '購入済商品',
        ]);

        /* キーワード「未購入」で検索 */
        $response = $this->get('/?keyword=未購入');
        $response->assertStatus(200);

        /* 一致する商品を表示することを確認 */
        $response->assertSee('未購入商品');

        /* 一致しない商品は表示しないことを確認 */
        $response->assertDontSee('購入済所品');
    }

    /* 検索状態がマイリストでも保持されている */
    public function test_search_02() {
        /* ユーザ作成 */
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        /* 商品を作成 */
        $product1 = Product::factory()->create([
            'user_id' => $user2->id,
            'name' => '未購入商品',
        ]);
        $product2 = Product::factory()->create([
            'user_id' => $user2->id,
            'name' => '購入済商品',
        ]);

        /* ユーザ1のいいね設定 */
        $like1 = Like::factory()->create(['product_id' => $product1->id, 'user_id' => $user1->id]);
        $like2 = Like::factory()->create(['product_id' => $product2->id, 'user_id' => $user1->id]);

        /* ログイン状態にする */
        $this->actingAs($user1);

        /* 「おすすめ」でキーワード「未購入」で検索 */
        $response = $this->get('/?tab=recommend&keyword=未購入');
        $response->assertStatus(200);

        /* 一致する商品を表示することを確認 */
        $response->assertSee('未購入商品');
        /* 一致しない商品は表示しないことを確認 */
        $response->assertDontSee('購入済所品');

        /* 「マイリスト」表示 */
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        /* 一致する商品を表示することを確認 */
        $response->assertSee('未購入商品');
        /* 一致しない商品は表示しないことを確認 */
        $response->assertDontSee('購入済所品');
    }
}
