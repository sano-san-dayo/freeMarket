<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UpdateProfileTest extends TestCase
{
    /* DBクリア */
    use DatabaseMigrations;

    /* ユーザ情報変更 */
    public function test_updateProfile_01() {
        /* DBへデータ投入 */
        $this->artisan('migrate:refresh');
        $this->seed();

        /* ユーザ情報取得 */
        $user = User::find(1);

        /* ログイン状態にする */
        $this->actingAs($user);

        /* プロフィール設定画面表示 */
        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        /* 初期値がせていされていることを確認 */
        /** プロフィール画像 **/
        $response->assertSee('ひらめいた男性.jpg');
        /** ユーザ名 **/
        $response->assertSee('user01');
        /** 郵便番号 **/
        $response->assertSee('111-2345');
        /** 住所 **/
        $response->assertSee('東京都渋谷区神南');
        /** 建物 **/
        $response->assertSee('マンション');
    }
}
