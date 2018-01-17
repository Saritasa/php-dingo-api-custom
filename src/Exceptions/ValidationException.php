<?php

namespace Saritasa\DingoApi\Exceptions;

use Dingo\Api\Contract\Debug\MessageBagErrors;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Implements output of validation errors custom format:
 *
 * errors: [{
 *  field: 'field_name', messages: ['error text']
 * }]
 *
 * instead of default
 *
 * errors: [{
 *   field_name: ['error text']
 * }]
 */
class ValidationException extends HttpException implements MessageBagErrors
{
    /**
     * @var \Illuminate\Support\MessageBag
     */
    private $errors;

    public function __construct(
        MessageBagContract $errors,
        $message = null,
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        $message = $message ?? trans('errors.validation_failed');
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous, $headers, $code);

        $this->errors = $this->transformErrors($errors);
    }

    private function transformErrors(MessageBagContract $errors)
    {
        $result = [];
        foreach ($errors->keys() as $key) {
            $messages = $errors->get($key);
            $result[] = [
                'field' => $key,
                'messages' => $messages
            ];
        }
        return new MessageBag($result);
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return $this->errors && $this->errors->any();
    }
}
