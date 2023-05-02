<?php

namespace Codememory\ApiBundle\Services\Decorator\Interfaces;

interface DecoratorHandlerInterface
{
    public function handle(DecoratorInterface $decorator, object $object, mixed ...$args): void;
}