<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class DetailTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 必要な情報表示 */
    public function test_detail_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(2);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* レスポンス内に画像ファイル名があることを確認 */
        $response->assertSee($product->image);
        
        /* 表示確認 */
        $response->assertSeeText($product->name);
        $response->assertSeeText($product->brand);
        $response->assertSeeText(number_format($product->price));
        $response->assertSeeText($product->description);

        /* カテゴリー */
        $categories = $product->categories;
        foreach ($categories as $category) {
            $response->assertSeeText($category->name);
        }

        /* 状態 */
        $condition = $product->condition_string;
        $response->assertSeeText($condition->name);

        /* いいね */
        $likes = $product->likes;
        $response->assertSeeText($likes->count());

        /* コメント */
        $comments = $product->comments;
        $response->assertSeeText($comments->count());
        foreach ($comments as $comment) {
            $comment_user = $comment->user;
            $response->assertSeeText($comment_user->name);
            $response->assertSeeText($comment->content);
        }
    }
}
