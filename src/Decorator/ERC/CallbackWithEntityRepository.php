<?php

namespace Codememory\ApiBundle\Decorator\ERC;

use Attribute;
use Codememory\EntityResponseControl\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class CallbackWithEntityRepository implements DecoratorInterface
{
    public function __construct(
        public object $entity,
        public string $callbackMethodName
    ) {
    }

    public function getHandler(): string
    {
        return CallbackWithEntityRepositoryHandler::class;
    }
}