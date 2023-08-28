<?php

namespace Codememory\ApiBundle\AttributeHandler;

use Codememory\ApiBundle\AttributeHandler\Interfaces\AttributeHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\DecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\DecoratorInterface;
use ReflectionAttribute;
use RuntimeException;

class AttributeHandler implements AttributeHandlerInterface
{
    public function __construct(
        private array $decoratorHandlers
    ) {
    }

    /**
     * @param array<int, ReflectionAttribute> $attributes
     */
    public function handle(array $attributes, object $object, mixed ...$args): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute instanceof ReflectionAttribute) {
                $this->attributeInstanceHandler($attribute->newInstance(), $object, ...$args);
            }
        }
    }

    /**
     * @param array<int, object> $instances
     */
    public function handleByAttributeInstances(array $instances, object $object, mixed ...$args): void
    {
        foreach ($instances as $attributeInstance) {
            $this->attributeInstanceHandler($attributeInstance, $object, ...$args);
        }
    }

    public function addDecoratorHandler(DecoratorHandlerInterface $decoratorHandler): AttributeHandlerInterface
    {
        if (!array_key_exists($decoratorHandler::class, $this->decoratorHandlers)) {
            $this->decoratorHandlers[$decoratorHandler::class] = $decoratorHandler;
        }

        return $this;
    }

    private function attributeInstanceHandler(object $attributeInstance, object $object, mixed ...$args): void
    {
        if ($attributeInstance instanceof DecoratorInterface) {
            $attributeName = $attributeInstance::class;

            if (!class_exists($attributeInstance->getHandler())) {
                throw new RuntimeException("Decorator handler {$attributeInstance->getHandler()} for {$attributeName} decorator not found");
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