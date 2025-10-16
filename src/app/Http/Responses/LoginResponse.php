<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        /* メール認証が未認証の場合 */
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        /* プロフィール未登録の場合 */
        if ($user && !$user->profile) {
            return redirect()->route('regist_profile');
        }

        /* メール認証済み、プロフィール登録済の場合 */
        return redirect()->route('index');
    }
}
