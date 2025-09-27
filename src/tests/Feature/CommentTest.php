<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class CommentTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* コメント送信(ログインユーザ) */
    public function test_comment_01() {
        /* DBへデータ投入 */
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(1);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* 現在のコメント件数を取得 */
        $beforeCount = $product->comments()->count();

        /* コメント実施 */
        $response = $this->post("/items/{$product->id}", [
            'comment' => 'コメントです',
        ]);
        $response->assertStatus(200);

        /* DBにコメントが保存されたか確認 */
        $this->assertDatabaseHas('comments', [
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'content'    => 'コメントです',
        ]);
    
        /* コメント件数を再取得 */
        $product->refresh();
        $afterCount = $product->comments()->count();

        /* コメント件数が増えていることを確認 */
        $this->assertEquals($beforeCount + 1, $afterCount);
    }

    /* コメント送信(未ログインユーザ) */
    public function test_comment_02() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* id = 2 の商品情報取得 */
        $product = Product::find(1);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* 現在のコメント件数を取得 */
        $beforeCount = $product->comments()->count();

        /* コメント実施 */
        $response = $this->post("/items/{$product->id}", [
            'comment' => 'コメントです',
        ]);

        /* コメント件数を再取得 */
        $product->refresh();
        $afterCount = $product->comments()->count();

        /* コメント件数が増えていないことを確認 */
        $this->assertEquals($beforeCount, $afterCount);
    }

    /* コメント未入力 */
    public function test_comment_03() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(1);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* コメント実施 */
        $response = $this->post("/items/{$product->id}", [
            'comment' => '',
        ]);

        /* セッションにエラーメッセージがあるか確認*/
        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);
    }

    /* コメントが256文字 */
    public function test_comment_04() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* id = 2 の商品情報取得 */
        $product = Product::find(1);

        /* 商品詳細画面表示 */
        $response = $this->get("/items/{$product->id}");
        $response->assertStatus(200);

        /* コメント実施 */
        $response = $this->post("/items/{$product->id}", [
            'comment' => '1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111112',
        ]);

        /* セッションにエラーメッセージがあるか確認*/
        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以下にしてください',
        ]);
    }
}
