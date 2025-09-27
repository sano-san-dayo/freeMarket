<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PaymentMethodTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 必要な情報表示 */
    public function test_paymentMethod_01() {
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
        $response = $this->get("/purchase/{$product->id}");
        $response->assertStatus(200);

        /* コンビニ支払いがないことを確認 */
        $response->assertDontSee('コンビニ支払い');

        /* カード支払いがないことを確認 */
        $response->assertDontSee('カード支払い');

        /* コンビニ支払いを選択 */
        $response = $this->get("/purchase/{$product->id}", [
            'payment-method' => 1,
        ]);
        $response->assertStatus(200);   

        /* コンビニ支払いがあることを確認 */
        $response->assertSee('コンビニ支払い');

        /* カード支払いがないことを確認 */
        $response->assertDontSee('カード支払い');

    }
}
