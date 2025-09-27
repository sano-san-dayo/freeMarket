<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{

    /* DBクリア */
    use RefreshDatabase;

    /* 購入処理 */
    public function test_purchase_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 4 の商品情報取得 */
        $product = Product::find(4);

        /* 購入商品テーブルに product_id = 4 がないことを確認 */
        $count = Purchase::where('product_id', $product->id)->count();
        $this->assertEquals(0, $count);

        // /* 商品詳細画面表示 */
        // $response = $this->get("/items/{$product->id}");
        // $response->assertStatus(200);

        /* 商品購入 */
         $response = $this->post("/purchase/{$product->id}", [
            'paymentMethod' => '1',
            'zipcode'  => '111-1111',
            'address'  => '東京都',
            'building' => 'マンション',
        ]);
        
        /* 購入商品テーブルに product_id = 4 があることを確認 */
        $count = Purchase::where('product_id', $product->id)->count();
        $this->assertEquals(1, $count);

    }

    /* 購入すると「Sold」表示 */
    public function test_purchase_02() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 4 の商品情報取得 */
        $product = Product::find(4);

        /* 商品一覧表示 */
        $response = $this->get('/product?tab=recommend');
        $response->assertStatus(200);

        /* HTML を Crawler に渡す */
        $crawler = new Crawler($response->getContent());

        /* 「Sold」非表示 を確認 */
        $this->assertEquals(0, $crawler->filter(".product-card:contains('{$product->name}') .product-card__sold")->count());

        /* 商品購入 */
         $response = $this->post("/purchase/{$product->id}", [
            'paymentMethod' => '1',
            'zipcode'  => '111-1111',
            'address'  => '東京都',
            'building' => 'マンション',
        ]);
        
        /* 商品一覧再表示 */
        $response = $this->get('/product?tab=recommend');
        $response->assertStatus(200);

        /* HTML を Crawler に渡す */
        $crawler = new Crawler($response->getContent());

        /* 「Sold」表示 を確認 */
        $this->assertEquals(1, $crawler->filter(".product-card:contains('{$product->name}') .product-card__sold")->count());
    }

    /* プロフィールの購入した商品一覧へ追加 */
    public function test_purchase_03() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 4 の商品情報取得 */
        $product = Product::find(4);

        /* プロファイルの購入した商品一覧表示 */
        $response = $this->get('/mypage?tab=buy');
        $response->assertStatus(200);

        /* 購入した商品にないことを確認 */
        $response->assertDontSeeText($product->name);

        /* 商品購入 */
         $response = $this->post("/purchase/{$product->id}", [
            'paymentMethod' => '1',
            'zipcode'  => '111-1111',
            'address'  => '東京都',
            'building' => 'マンション',
        ]);
        
        /* プロファイルの購入した商品一覧再表示 */
        $response = $this->get('/mypage?tab=buy');
        $response->assertStatus(200);

        /* 購入した商品にあることを確認 */
        $response->assertSeeText($product->name);
    }
 }
