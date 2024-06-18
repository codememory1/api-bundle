<?php

namespace Codememory\ApiBundle\Exceptions;

use RuntimeException;
use Throwable;

class DecoratorHandlerNotRegisteredException extends RuntimeException
{
    public function __construct(
        public readonly string $decorator,
        public readonly string $decoratorHandler,
        public readonly string $registrationTag,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("The \"{$decoratorHandler}\" handler for the \"{$decorator}\" decorator is not registered. Register it using the \"{$registrationTag}\" tag", $code, $previous);
    }
}