<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /* ユーザ登録画面表示 */
    public function create() {
        return view('regist_user');
    }

    /* ユーザ登録処理 */
    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /* ログイン状態にする */
        Auth::login($user);

        /* 認証メール送信 */
        event(new Registered($user));

        return view('verify_notice');
    }

    /* 認証メール再送 */
    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        
        // return back()->with('message', '認証メールを再送しました');
        return redirect()->route('verification.notice')->with('message', '認証メールを再送しました');
    }

    /* メール認証成功 */
    public function success_verify(Request $request, $id, $hash) {
        /* ユーザ情報取得 */
        $user = User::find($id);

        if (!$user) {
            abort(404, 'User not found.');
        }

        /* ハッシュ検証（メールアドレスの SHA1 値と比較） */
        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }

        /* 未認証なら認証状態にする */
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        /* 自動ログイン */
        Auth::login($user);

        /* プロフィール登録画面へ */
        // return redirect('/mypage/profile');
        return redirect()->route('regist_profile');
    }}
