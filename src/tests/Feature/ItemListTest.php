<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class ItemListTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 全商品一覧表示 */
    public function test_list_01() {
        /* ユーザ作成 */
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        /* ユーザそれぞれの商品を作成 */
        $productsUser1 = Product::factory()->count(5)->create(['user_id' => $user1->id]);
        $productsUser2 = Product::factory()->count(3)->create(['user_id' => $user2->id]);
        $allProducts = $productsUser1->concat($productsUser2);

        /* 商品一覧画面へアクセス */
        $response = $this->get('/');

        /* すべての商品が表示されていることを確認 */
        foreach ($allProducts as $product) {
            $response->assertSeeText($product->name);
        }
    }

    /* 購入済委商品は「Sold」表示 */
    public function test_list_02() {
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

        /* 商品一覧取得 */
        $response = $this->get('/');

        /* HTML を Crawler に渡す */
        $crawler = new Crawler($response->getContent());

        /* 未購入商品には「Sold」非表示 を確認 */
        $this->assertEquals(0, $crawler->filter('.product-card:contains("未購入商品") .product-card__sold')->count());

        /* 購入済商品には「Sold」表示 を確認 */
        $this->assertEquals(1, $crawler->filter('.product-card:contains("購入済商品") .product-card__sold')->count());
    }

    /* 自分が出品した商品は表示されない */
    public function test_list_03() {
        /* ユーザ作成 */
        $user = User::factory()->create();

        /* ユーザの商品を3件作成 */
        $myProducts = Product::factory()->count(3)->create(['user_id' => $user->id]);

        /* 商品一覧画面へアクセス */
        $response = $this->actingAs($user)->get('/?tab=recommend');

        /* 自分の商品が含まれていないことを確認 */
        foreach ($myProducts as $product) {
            $response->assertDontSeeText($product->name);
        }
    }
}
