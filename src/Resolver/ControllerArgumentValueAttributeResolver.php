<?php

namespace Codememory\ApiBundle\Resolver;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorRegistryInterface;
use Codememory\ApiBundle\Exceptions\DecoratorHandlerNotRegisteredException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ControllerArgumentValueAttributeResolver implements ValueResolverInterface
{
    public function __construct(
        private ControllerArgumentValueResolverDecoratorRegistryInterface $controllerArgumentValueDecoratorRegistry
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $value = [];

        if ($this->supports($argument)) {
            foreach ($this->getAttributes($argument) as $attribute) {
                $value = $this->attributeHandler($attribute, $request, $argument);
            }
        }

        return $value;
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return count($argument->getAttributes()) > 0;
    }

    /**
     * @return array<int, ControllerArgumentValueResolverDecoratorInterface>
     */
    private function getAttributes(ArgumentMetadata $argument): array
    {
        return array_filter($argument->getAttributes(), static fn (object $attribute) => $attribute instanceof ControllerArgumentValueResolverDecoratorInterface);
    }

    private function attributeHandler(ControllerArgumentValueResolverDecoratorInterface $attribute, Request $request, ArgumentMetadata $argument): iterable
    {
        $handler = $this->controllerArgumentValueDecoratorRegistry->getHandler($attribute->getHandler());

        if (null === $handler) {
            throw new DecoratorHandlerNotRegisteredException($attribute::class, $attribute->getHandler(), ApiBundle::DECORATOR_TAG);
        }

        return $this->controllerArgumentValueDecoratorRegistry->getHandler($attribute->getHandler())->handle($attribute, $request, $argument);
    }
}