<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

interface ControllerArgumentDecoratorHandlerInterface
{
    public function handle(ControllerArgumentDecoratorInterface $decorator, ArgumentMetadata $argumentMetadata, object $controller, object $value): void;
}