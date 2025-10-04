<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyController extends Controller
{
    /* メール認証誘導画面表示 */
    public function notice() {
        return view('verify_notice');
    }

    /* メール認証 */
    public function verify(Request $request) {
        dd($request);
        if ($request->user()->hasVerifyedEmail()) {
            return redirect()->route('profile.create');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->yser()));
        }

        return redirect()->route('profile.create');
    }

    /* 認証メール再送 */
    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを再送しました。');
    }
}
