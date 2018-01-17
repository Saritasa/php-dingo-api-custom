<?php

namespace Saritasa\DingoApi\Exceptions;

use Dingo\Api\Exception\Handler as DingoApiHandler;
use Dingo\Api\Facade\API;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Debug\ExceptionHandler as IlluminateExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Saritasa\DingoApi\Exceptions\ValidationException as CustomValidationException;
use Saritasa\Exceptions\PermissionsException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ApiExceptionHandler extends DingoApiHandler
{
    /**
     * ApiHandler constructor.
     * @param IlluminateExceptionHandler $parentHandler
     */
    public function __construct(IlluminateExceptionHandler $parentHandler)
    {
        parent::__construct($parentHandler, config('api.errorFormat'), config('api.debug'));
        $this->registerCustomHandlers();
    }

    /**
     * Register certain handlers via Dingo\API facade
     *
     * We can't pass class method as callback directly,
     * so we wrap them into closures.
     */
    private function registerCustomHandlers()
    {
        API::error(function (UnauthorizedHttpException $e) {
            return $this->handleUnauthorized($e);
        });
        API::error(function (AuthorizationException $e) {
            return $this->handleAuthorizationError($e);
        });
        Api::error(function (PermissionsException $e) {
            return $this->handleAuthorizationError($e);
        });
        API::error(function (ValidationException $e) {
            return $this->handleValidation($e);
        });
        API::error(function (ModelNotFoundException $e) {
            return $this->handleModelNotFound($e);
        });
    }

    /**
     * Security gateway throws AuthorizationException without HTTP code set.
     * Render AccessDeniedHttpException instead to produce 403 HTTP code instead of 500
     *
     * @param \Exception $e
     * @return Response
     */
    public function handleAuthorizationError(\Exception $e)
    {
        $e = new AccessDeniedHttpException($e->getMessage(), $e);
        return $this->handle($e);
    }


    /**
     * Dingo API does not handle default Laravel validation exception.
     * Wrap it into ValidationHttpException, processed properly
     *
     * @param ValidationException $e
     * @return Response
     */
    public function handleValidation(ValidationException $e)
    {
        $e = new CustomValidationException($e->validator->getMessageBag(), $e->getMessage(), $e, [], $e->getCode());
        return $this->handle($e);
    }

    /**
     * Replace model not found exception to guarantee 404 error instead of 500
     *
     * @param ModelNotFoundException $e
     * @return Response
     */
    private function handleModelNotFound(ModelNotFoundException $e)
    {
        $e = new NotFoundHttpException($e->getMessage(), $e, $e->getCode());
        return $this->handle($e);
    }

    /**
     * Add special handling for expired JWT Token.
     * According to HTTP specification, we must return code 401, but allow client application
     * determine, that it should refresh auth token by additional flag:
     * code = 498
     * in body response
     *
     * @param UnauthorizedHttpException $e
     * @return Response
     */
    private function handleUnauthorized(UnauthorizedHttpException $e)
    {
        if ($e->getPrevious() instanceof TokenExpiredException) {
            $e = new UnauthorizedHttpException('JWTAuth', $e->getMessage(), $e, 498);
        }
        return $this->handle($e);
    }
}
