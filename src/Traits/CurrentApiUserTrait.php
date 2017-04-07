<?php

namespace App\Extensions;

use App\Models\User;
use Dingo\Api\Auth\Auth;

trait CurrentApiUserTrait
{
    protected function user(): User
    {
        return app(Auth::class)->user();
    }
}