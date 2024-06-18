<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerArgumentDecoratorInterface
{
    public function getHandler(): string;
}