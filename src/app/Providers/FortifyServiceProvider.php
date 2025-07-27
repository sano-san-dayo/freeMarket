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
use Illuminate\Support\Facades\Redirect;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
            return view('profile');
        });


        // ログイン成功後のリダイレクト先
        Fortify::authenticateThrough(function (Request $request) {
            return [
                \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
                function ($request) {
                    // ログイン成功時の商品一覧画面へのリダイレクト
                    return Redirect::intended('/products');
                },
            ];
        });
        // ここでログイン用の RateLimiter を設定
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user &&
                \Hash::check($request->password, $user->password)) {

                // メール未認証ユーザは弾く
                if (!$user->hasVerifiedEmail()) {
                    throw ValidationException::withMessages([
                        Fortify::username() => __('このアカウントはメール認証が完了していません。'),
                    ]);
                }

                return $user;
            }

            return null;
        });   
    }
}

