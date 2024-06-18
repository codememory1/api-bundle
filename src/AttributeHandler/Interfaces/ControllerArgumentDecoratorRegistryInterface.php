<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerArgumentDecoratorRegistryInterface
{
    public function addHandler(ControllerArgumentDecoratorHandlerInterface $handler): self;

    public function existHandler(ControllerArgumentDecoratorHandlerInterface|string $handler): bool;

    public function removeHandler(ControllerArgumentDecoratorHandlerInterface|string $handler): bool;

    public function getHandler(string $handler): ?ControllerArgumentDecoratorHandlerInterface;

    /**
     * @return array<string, ControllerArgumentDecoratorHandlerInterface>
     */
    public function getListHandlers(): array;
}