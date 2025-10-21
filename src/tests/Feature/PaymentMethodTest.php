<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PaymentMethodTest extends TestCase
{
    /* DBクリア */
    use DatabaseMigrations;

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
        $product = Product::find(3);

        /* コンビニ支払いを選択 */
        $response = $this->get("/purchase/{$product->id}?payment-method=1");
        $response->assertStatus(200);   

        /* コンビニ支払いがあることを確認 */
        $response->assertSeeInOrder([
            '<span class="payment-method-span">',
            'コンビニ支払い'
        ], false);
    }
}
