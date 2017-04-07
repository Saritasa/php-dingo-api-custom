<?php

namespace Saritasa\DingoApi;

use Saritasa\DingoApi\Exceptions\ApiExceptionHandler;
use Illuminate\Support\ServiceProvider;

class SaritasaDingoApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApiExceptionHandler();
    }

    private function registerApiExceptionHandler()
    {
        $this->app->singleton('app.api.exception', ApiExceptionHandler::class);
        app('app.api.exception');
    }
}