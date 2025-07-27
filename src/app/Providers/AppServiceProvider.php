<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// 2025/07/20 下記2行追加
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\RegisterResponse as CustomRegisterResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // 2025/07/20 下記１行追加
        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
