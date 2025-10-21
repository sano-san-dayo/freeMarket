<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /* DBクリア */
    use DatabaseMigrations;

    /* 正常 */
    public function test_logout_01() {
        /* ユーザ作成 */
        $user = User::factory()->create();

        /* ログイン状態にする */
        $this->actingAs($user);

        /* ログインしていることを確認 */
        $this->assertAuthenticated();
        
        /* ログアウト実施 */
        $response = $this->post('/logout');
 
        /* セッションから認証が外れていることを確認 */
        $this->assertGuest();

        /* ログアウト後にログイン画面表示を確認 */
        $response->assertRedirect('/login');
    }

}
