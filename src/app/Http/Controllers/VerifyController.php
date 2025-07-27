<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function notice() {
        return view('verify_notice');
    }

    public function verify(Request $request) {
        if ($request->user()->hasVerifyedEmail()) {
            return redirect()->route('profile.create');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->yser()));
        }

        return redirect()->route('profile.create');
    }

    public function resend(Request $request) {
        // if ($request->user()->hasVerifyedEmail()) {
        //     return redirect()->route('products.index');
        // }
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを再送しました。');
    }
}
