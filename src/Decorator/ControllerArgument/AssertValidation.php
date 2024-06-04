<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgument;

use Attribute;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorInterface;

#[Attribute(Attribute::TARGET_PARAMETER)]
class AssertValidation implements ControllerArgumentDecoratorInterface
{
    public function getHandler(): string
    {
        return AssertValidationHandler::class;
    }
}