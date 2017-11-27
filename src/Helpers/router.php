<?php

if (!function_exists('apiRoute')) {
    /**
     * Generate the URL to a named API route with preserve scheme.
     *
     * @param  string $name Route name
     * @param  array $parameters Route parameters
     * @param  bool $absolute If TRUE returns absolute URL
     *
     * @return string
     */
    function apiRoute($name, array $parameters = [], $absolute = true)
    {
        $scheme = \Saritasa\Middleware\RequestChecker::isSecure(request()) ? 'https' : 'http';
        $router = version(config('api.version'));

        $isOldLaravel = version_compare(app()->version(), '5.4.0', '<');
        if ($isOldLaravel) {
            $router->forceSchema($scheme);
        } else {
            $router->forceScheme($scheme);
        }

        return $router->route($name, $parameters, $absolute);
    }
}
