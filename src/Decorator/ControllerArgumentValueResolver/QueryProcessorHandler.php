<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgumentValueResolver;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorInterface;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class QueryProcessorHandler implements ControllerArgumentValueResolverDecoratorHandlerInterface
{
    public function __construct(
        private AssertValidatorInterface $assertValidator
    ) {
    }

    /**
     * @param QueryProcessor $decorator
     */
    public function handle(ControllerArgumentValueResolverDecoratorInterface $decorator, Request $request, ArgumentMetadata $argumentMetadata): iterable
    {
        $data = $this->getValue($decorator->name, $request);
        $processor = new ($argumentMetadata->getType())($data);

        $this->assertValidator->validate($processor);

        yield $processor;
    }

    private function getValue(string $name, Request $request): array
    {
        if ($request->query->has($name)) {
            $value = $request->query->all()[$name];

            return !is_array($value) ? [$value] : $value;
        }

        return [];
    }
}