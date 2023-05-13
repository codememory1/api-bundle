<?php

namespace Codememory\ApiBundle\Services\Decorator;

use Codememory\ApiBundle\Services\Decorator\Interfaces\DecoratorHandlerInterface;
use Codememory\ApiBundle\Services\Decorator\Interfaces\DecoratorInterface;
use ReflectionAttribute;
use RuntimeException;

class Decorator
{
    public function __construct(
        private readonly array $decoratorHandlers
    ) {
    }

    /**
     * @param array<int, ReflectionAttribute> $attributes
     */
    public function handle(array $attributes, object $object, mixed ...$args): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute instanceof ReflectionAttribute) {
                $this->handleByAttributeInstances($attribute->newInstance(), $object, ...$args);
            }
        }
    }

    /**
     * @param array<int, object> $instances
     */
    public function handleByAttributeInstances(array $instances, object $object, mixed ...$args): void
    {
        foreach ($instances as $attributeInstance) {
            if ($attributeInstance instanceof DecoratorInterface) {
                $attributeName = $attributeInstance::class;

                if (!class_exists($attributeInstance->getHandler())) {
                    throw new RuntimeException("Decorator handler {$attributeInstance->getHandler()} for $attributeName decorator not found");
                }

                if (!array_key_exists($attributeInstance->getHandler(), $this->decoratorHandlers)) {
                    throw new RuntimeException("Service {$attributeInstance->getHandler()} not found");
                }

                $decoratorHandler = $this->decoratorHandlers[$attributeInstance->getHandler()];

                if (!$decoratorHandler instanceof DecoratorHandlerInterface) {
                    throw new RuntimeException(sprintf('The %s decorator handler must implement the %s interface', $attributeInstance->getHandler(), DecoratorHandlerInterface::class));
                }

                $decoratorHandler->handle($attributeInstance, $object, ...$args);
            }
        }
    }
}