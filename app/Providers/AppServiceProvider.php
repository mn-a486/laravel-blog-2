<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
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
        // 本番環境では https を強制
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // 🚫 旧Heroku URL からのアクセスを拒否
            if (request()->getHost() === '旧アプリ名.herokuapp.com') {
                abort(403, 'Access denied');
            }
        }
    }
}