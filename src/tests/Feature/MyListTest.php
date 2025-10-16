<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Like;

class MyListTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* いいねした商品だけ表示 */
    public function test_mylist_01() {
        /* ユーザ作成 */
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        /* ユーザの商品作成 */
        $product1 = Product::factory()->create([
            'user_id' => $user2->id,
            'name' => '商品2-1',
        ]);
        $product2 = Product::factory()->create([
            'user_id' => $user2->id,
            'name' => '商品2-2',
        ]);
        $product3 = Product::factory()->create([
            'user_id' => $user1->id,
            'name' => '商品1-1',
        ]);

        $products = Product::factory()->count(5)->create(['user_id' => $user2->id]);

        /* ユーザ1のいいね設定 */
        $like1 = Like::factory()->create(['product_id' => $product1->id, 'user_id' => $user1->id]);
        /* ユーザ2のいいね設定 */
        $like2 = Like::factory()->create(['product_id' => $product3->id, 'user_id' => $user2->id]);

        /* ログイン状態にする */
        $this->actingAs($user1);

        /* マイリスト表示 */
        $response = $this->get('/?tab=mylist');

        /* マイリストにユーザ1がいいねした商品を表示することを確認 */
        $response->assertSeeText($product1->name);

        /* マイリストにユーザ1がいいねしていない商品を表示しないことを確認 */
        $response->assertDontSeeText($product2->name);

        /* マイリストにユーザ2がいいねした商品を表示しないことを確認 */
        $response->assertDontSeeText($product3->name);
    }

    /* 購入済商品は「Sold」と表示 */
    public function test_mylist_02() {
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

        /* 購入情報作成 */
        Purchase::factory()->create([
            'user_id' => $user1->id,
            'product_id' => $product2->id,
        ]);

        /* ユーザのいいね設定 */
        $like1 = Like::factory()->create(['product_id' => $product1->id, 'user_id' => $user1->id]);
        $like2 = Like::factory()->create(['product_id' => $product2->id, 'user_id' => $user1->id]);

        /* ログイン状態にする */
        $this->actingAs($user1);

        /* マイリスト表示 */
        $response = $this->get('/?tab=mylist');

        /* HTML を Crawler に渡す */
        $crawler = new Crawler($response->getContent());

        /* 未購入商品には「Sold」非表示 を確認 */
        $this->assertEquals(0, $crawler->filter('.product-card:contains("未購入商品") .product-card__sold')->count());

        /* 購入済商品には「Sold」表示 を確認 */
        $this->assertEquals(1, $crawler->filter('.product-card:contains("購入済商品") .product-card__sold')->count());
    }

    /* 未認証の場合何も表示されない */
    public function test_mylist_03() {
        /* ユーザ作成 */
        $user = User::factory()->create();

        /* ユーザの商品を3件作成 */
        $products = Product::factory()->count(3)->create(['user_id' => $user->id]);

        /* 商品一覧画面へアクセス */
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        /* 商品が1件も無いので、商品カードのHTMLが存在しないことを確認 */
        $response->assertDontSee('<div class="product-card"', false);
    }
}
