<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

use ReflectionAttribute;

interface AttributeHandlerInterface
{
    /**
     * @param array<int, ReflectionAttribute> $attributes
     */
    public function handle(array $attributes, object $object, mixed ...$args): void;

    /**
     * @param array<int, object> $instances
     */
    public function handleByAttributeInstances(array $instances, object $object, mixed ...$args): void;

    public function addDecoratorHandler(DecoratorHandlerInterface $decoratorHandler): self;
}