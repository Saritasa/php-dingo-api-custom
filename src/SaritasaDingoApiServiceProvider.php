<?php

namespace Saritasa\DingoApi;

use Dingo\Api\Provider\DingoServiceProvider;
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
        $this->register(DingoServiceProvider::class);
    }

    private function registerApiExceptionHandler()
    {
        $this->app->singleton('app.api.exception', ApiExceptionHandler::class);
        app('app.api.exception');
    }
}