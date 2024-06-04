<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerArgumentValueResolverDecoratorInterface
{
    public function getHandler(): string;
}