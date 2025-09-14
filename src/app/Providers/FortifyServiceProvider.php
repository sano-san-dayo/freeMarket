<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\LoginResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // LoginResponseを差し替え
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        /* 各ビューを設定 */
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            // return view('profile');
            return view('verify_notice');
        });


        // ログイン成功後のリダイレクト先
        Fortify::authenticateThrough(function (Request $request) {
            return [
                AttemptToAuthenticate::class,        // パスワードチェック
                PrepareAuthenticatedSession::class,  // セッションにログイン情報を保存
            ];
        });

        // ログイン試行回数制限
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        // カスタムログイン処理
        Fortify::authenticateUsing(function (Request $request) {
            // バリデーション処理
            Validator::make($request->all(), [
                'email'    => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:8'],
            ])->validate();

            // ユーザ取得
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {

                /* メール未認証の場合、ログインは成功させる */
                if (!$user->hasVerifiedEmail()) {
                    session(['must_verify' => true]);
                }

                return $user;
            }

            // 認証失敗
            return null;
        });
    }
}

