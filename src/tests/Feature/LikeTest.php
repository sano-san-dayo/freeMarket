<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class LikeTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* いいね実施 */
    public function test_like_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(3);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(2);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* 現在のいいね件数を取得 */
        $countLike = $product->likes()->count();

        /* いいねアイコンをクリック */
        $response = $this->post("/like/{$product->id}");
        $response->assertStatus(200);

        /* いいね件数確認 */
        $response->assertSeeText($countLike + 1);
    }

    /* いいね実施でアイコンが代わる */
    public function test_like_02() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(3);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(2);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* いいね実施前のアイコン表示を確認 */
        $response->assertSee('星アイコン.png');

        /* いいねアイコンをクリック */
        $response = $this->post('/like/2');
        $response->assertStatus(200);

        /* アイコンが変わっていることを確認 */
        $response->assertSee('星アイコン_お気に入り.png');
    }

    /* いいね再実施でいいね解除 */
    public function test_like_03() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(3);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(2);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* 現在のいいね件数を取得 */
        $countLike = $product->likes()->count();

        /* いいねアイコンをクリック */
        $response = $this->post("/like/{$product->id}");
        $response->assertStatus(200);

        /* いいね件数確認 */
        $response->assertSeeText($countLike + 1);

        /* いいねアイコンを再度クリック */
        $response = $this->post("/like/{$product->id}");
        $response->assertStatus(200);

        /* いいね件数確認 */
        $response->assertSeeText($countLike);
    }
}
