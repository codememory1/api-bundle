<?php

namespace Codememory\ApiBundle\Exceptions;

use RuntimeException;

class DecoratorHandlerNotRegisteredException extends RuntimeException
{
    public function __construct(string $decorator, string $decoratorHandler, string $registrationTag)
    {
        parent::__construct("The \"{$decoratorHandler}\" handler for the \"{$decorator}\" decorator is not registered. Register it using the \"{$registrationTag}\" tag");
    }
}