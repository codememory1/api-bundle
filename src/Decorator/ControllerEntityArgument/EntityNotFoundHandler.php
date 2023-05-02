<?php

namespace Codememory\ApiBundle\Decorator\ControllerEntityArgument;

use Codememory\ApiBundle\Services\Decorator\Interfaces\DecoratorHandlerInterface;
use Codememory\ApiBundle\Services\Decorator\Interfaces\DecoratorInterface;

final class EntityNotFoundHandler implements DecoratorHandlerInterface
{
    /**
     * @param EntityNotFound $decorator
     */
    public function handle(DecoratorInterface $decorator, object $object, ...$args): void
    {
        if (null === $args[0]) {
            throw $decorator->exceptionClass::{$decorator->method}();
        }
    }
}