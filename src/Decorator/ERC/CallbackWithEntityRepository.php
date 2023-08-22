<?php

namespace Codememory\ApiBundle\Decorator\ERC;

use Codememory\EntityResponseControl\Interfaces\DecoratorInterface;

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