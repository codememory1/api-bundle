<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerArgumentValueResolverDecoratorRegistryInterface
{
    public function addHandler(ControllerArgumentValueResolverDecoratorHandlerInterface $handler): self;

    public function existHandler(ControllerArgumentValueResolverDecoratorHandlerInterface|string $handler): bool;

    public function removeHandler(ControllerArgumentValueResolverDecoratorHandlerInterface|string $handler): bool;

    public function getHandler(string $handler): ?ControllerArgumentValueResolverDecoratorHandlerInterface;

    /**
     * @return array<string, ControllerArgumentValueResolverDecoratorHandlerInterface>
     */
    public function getListHandlers(): array;
}