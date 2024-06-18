<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgument;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorInterface;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertValidatorInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class AssertValidationHandler implements ControllerArgumentDecoratorHandlerInterface
{
    public function __construct(
        private AssertValidatorInterface $assertValidator
    ) {
    }

    public function handle(ControllerArgumentDecoratorInterface $decorator, ArgumentMetadata $argumentMetadata, object $controller, object $value): void
    {
        $this->assertValidator->validate($value);
    }
}