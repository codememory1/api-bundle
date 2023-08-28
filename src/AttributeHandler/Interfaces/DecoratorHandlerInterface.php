<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface DecoratorHandlerInterface
{
    public function handle(DecoratorInterface $decorator, object $object, mixed ...$args): void;
}