<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /* DBクリア */
    use DatabaseMigrations;

    /* メールアドレス未入力 */
    public function test_login_01() {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '11111111',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /* パスワード未入力 */
    public function test_login_02() {
        $response = $this->post('/login', [
            'email' => 'user01@foo.bar',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /* 入力情報誤り */
    public function test_login_03() {
        $response = $this->post('/login', [
            'email' => 'dummy@foo.bar',
            'password' => '11111111',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /* 正常 */
    public function test_login_04() {
        /* ユーザ作成 */
        $user = User::factory()->create([
            'email' => 'hoge@foo.bar',
            'password' => Hash::make('11111111'),
            'email_verified_at' => now(),
        ]);

        /* ログイン処理 */
        $response = $this->post('/login', [
            'email' => 'hoge@foo.bar',
            'password' => '11111111',
        ]);

        /* 認証されていることを確認 */
        $this->assertAuthenticatedAs($user);
    }
}
