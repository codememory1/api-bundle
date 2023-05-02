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
            $attributeInstance = $attribute->newInstance();

            if ($attributeInstance instanceof DecoratorInterface) {
                if (!class_exists($attributeInstance->getHandler())) {
                    throw new RuntimeException("Decorator handler {$attributeInstance->getHandler()} for {$attribute->getName()} decorator not found");
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