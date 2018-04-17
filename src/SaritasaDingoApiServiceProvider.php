<?php

namespace Saritasa\DingoApi;

use Dingo\Api\Provider\LaravelServiceProvider as DingoApiServiceProvider;
use Saritasa\DingoApi\Exceptions\ApiExceptionHandler;
use Illuminate\Support\ServiceProvider;

class SaritasaDingoApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(DingoApiServiceProvider::class);
        $this->registerApiExceptionHandler();

        $this->registerRouterHelper();

        $configPath = __DIR__ . '/../config/api.php';
        $this->mergeConfigFrom($configPath, 'api');
    }

    private function registerApiExceptionHandler(): void
    {
        $this->app->singleton('app.api.exception', ApiExceptionHandler::class);
        $this->app->make('app.api.exception');
    }

    private function registerRouterHelper(): void
    {
        require_once __DIR__ . '/Helpers/router.php';
    }
}
