<?php

namespace Codememory\ApiBundle\Decorator\ControllerEntityArgument;

use Attribute;
use Codememory\ApiBundle\Services\Decorator\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class EntityNotFound implements DecoratorInterface
{
    public function __construct(
        public readonly string $exceptionClass,
        public readonly string $method
    ) {
    }

    public function getHandler(): string
    {
        return EntityNotFoundHandler::class;
    }
}