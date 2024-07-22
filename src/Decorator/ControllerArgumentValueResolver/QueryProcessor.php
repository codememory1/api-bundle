<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgumentValueResolver;

use Attribute;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
class QueryProcessor implements ControllerArgumentValueResolverDecoratorInterface
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getHandler(): string
    {
        return QueryProcessorHandler::class;
    }
}