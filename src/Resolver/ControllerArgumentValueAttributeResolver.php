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
        if ($this->supports($argument)) {
            $value = [];

            foreach ($argument->getAttributes() as $attribute) {
                $value = $this->attributeHandler($attribute, $request, $argument);
            }

            return $value;
        }

        return [];
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return count($argument->getAttributes()) > 0;
    }

    private function attributeHandler(object $attribute, Request $request, ArgumentMetadata $argument): iterable
    {
        if ($attribute instanceof ControllerArgumentValueResolverDecoratorInterface) {
            $handler = $this->controllerArgumentValueDecoratorRegistry->getHandler($attribute->getHandler());

            if (null === $handler) {
                throw new DecoratorHandlerNotRegisteredException($attribute::class, $attribute->getHandler(), ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_TAG);
            }

            return $this->controllerArgumentValueDecoratorRegistry->getHandler($attribute->getHandler())->handle($attribute, $request, $argument);
        }

        return [];
    }
}