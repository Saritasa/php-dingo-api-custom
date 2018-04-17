<?php

namespace Saritasa\DingoApi\Traits;

use Dingo\Api\Auth\Auth;

trait CurrentApiUserTrait
{
    protected function user()
    {
        return app(Auth::class)->user();
    }
}
