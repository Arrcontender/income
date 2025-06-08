<?php

namespace App\Providers;

use App\Services\Bybit\BybitApi;
use App\Services\Bybit\BybitApiInterface;
use App\Services\Bybit\BybitApiMock;
use App\Services\TInvest\TInvestApi;
use App\Services\TInvest\TInvestApiInterface;
use App\Services\TInvest\TInvestApiMock;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TInvestApiInterface::class, function (Application $app) {
            return app()->isLocal() ? new TInvestApiMock() : new TInvestApi();
        });

        $this->app->singleton(BybitApiInterface::class, function (Application $app) {
            return app()->isLocal() ? new BybitApiMock() : new BybitApi();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
