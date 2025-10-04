<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ChangeAddressTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 配送先変更反映確認 */
    public function test_changeAddress_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* 商品購入画面表示 */
        $response = $this->get('/purchase/1');
        $response->assertStatus(200);

        /* 現在の住所確認 */
        $response->assertSee('111-2345');
        $response->assertSee('東京都渋谷区神南');
        $response->assertSee('マンション');

        /* 送付先住所変更画面で住所変更 */
        // $response = $this->post(route('purchase.address', [$product_id = 1, $paymentMethod = 1]), [
        $response = $this->post('/purchase/address', [
            'zipcode'  => '987-6543',
            'address'  => '神奈川県横浜市',
            'building' => 'アパート',
            'product_id' => 1,
            'paymentMethod' => 1,
        ]);

        // /* 商品購入画面再表示 */
        // $response = $this->get('/purchase/1');
        $response->assertStatus(200);
        $response->assertViewIs('.purchase.buy');

        /* 送付先住所変更確認 */
        $response->assertSee('987-6543');
        $response->assertSee('神奈川県横浜市');
        $response->assertSee('アパート');
    }
}
