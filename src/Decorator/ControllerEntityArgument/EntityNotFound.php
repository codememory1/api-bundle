<?php

namespace Codememory\ApiBundle\Decorator\ControllerEntityArgument;

use Attribute;
use Codememory\ApiBundle\AttributeHandler\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class EntityNotFound implements DecoratorInterface
{
    public function __construct(
        public string $exceptionClass,
        public string $method
    ) {
    }

    public function getHandler(): string
    {
        return EntityNotFoundHandler::class;
    }
}