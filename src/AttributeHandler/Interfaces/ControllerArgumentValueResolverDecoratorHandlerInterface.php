<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

interface ControllerArgumentValueResolverDecoratorHandlerInterface
{
    public function handle(ControllerArgumentValueResolverDecoratorInterface $decorator, Request $request, ArgumentMetadata $argumentMetadata): iterable;
}