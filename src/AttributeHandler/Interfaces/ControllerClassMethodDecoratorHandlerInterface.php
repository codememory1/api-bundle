<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerClassMethodDecoratorHandlerInterface
{
    public function handler(ControllerClassMethodDecoratorInterface $decorator, object $controller): void;
}