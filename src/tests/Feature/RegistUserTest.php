<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistUserTest extends TestCase
{
    /* DBクリア */
    use RefreshDatabase;

    /* 名前未入力 */
    public function test_register_01() {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'hoge@foo.bar',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }
    
    /* メールアドレス未入力 */
    public function test_register_02() {
        $response = $this->post('/register', [
            'name' => 'testUser01',
            'email' => '',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /* パスワード未入力 */
    public function test_register_03() {
        $response = $this->post('/register', [
            'name' => 'testUser01',
            'email' => 'hoge@foo.bar',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /* パスワードが7文字以下 */
    public function test_register_04() {
        $response = $this->post('/register', [
            'name' => 'testUser01',
            'email' => 'hoge@foo.bar',
            'password' => '1111',
            'password_confirmation' => '1111',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    /* パスワードと確認用パスワード不一致 */
    public function test_register_05() {
        $response = $this->post('/register', [
            'name' => 'testUser01',
            'email' => 'hoge@foo.bar',
            'password' => '11111111',
            'password_confirmation' => '11111112',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    /* 正常 */
    public function test_register_06() {
        $response = $this->post('/register', [
            'name' => 'testUser01',
            'email' => 'hoge@foo.bar',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);

        /* DBに登録されていることを確認 */
        $this->assertDatabaseHas('users', [
            'email' => 'hoge@foo.bar',
        ]);

        /* 認証誘導画面を表示していることを確認 */
        $response->assertSee('認証はこちらから');
    }
}
