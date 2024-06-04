<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgumentValueResolver;

use Attribute;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MapEntityFromRoute implements ControllerArgumentValueResolverDecoratorInterface
{
    public function __construct(
        public readonly string $routeParam,
        public readonly string $entityKey = 'id',
        public readonly ?string $entityClass = null,
        public readonly ?string $repository = null,
        public readonly ?string $repositoryMethod = null,
        public readonly ?string $throwClass = null,
        public readonly ?string $throwStaticMethod = null
    ) {
    }

    public function getHandler(): string
    {
        return MapEntityFromRouteHandler::class;
    }
}