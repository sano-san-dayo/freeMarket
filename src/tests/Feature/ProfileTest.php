<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileTest extends TestCase
{
    /* DBクリア */
    use DatabaseMigrations;

    /* ユーザ情報取得 */
    public function test_profile_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* プロフィール画面表示 */
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        /* 必要な情報が取得できていることを確認 */
        /** プロフィール画像 **/
        $response->assertSee('ひらめいた男性.jpg');
        /** ユーザ名 **/
        $response->assertSeeText('user01');
        /** 出品した商品一覧 **/
        $response->assertSeeText('腕時計');
        $response->assertSeeText('ノートPC');
        $response->assertSeeText('マイク');
        /** 購入した商品一覧 **/
        $response = $this->get('/mypage?tab=purchase');
        $response->assertStatus(200);
        $response->assertSeeText('HDD');
    }
}
