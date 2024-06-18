<?php

namespace Codememory\ApiBundle\AttributeHandler\Interfaces;

interface ControllerClassMethodDecoratorRegistryInterface
{
    public function addHandler(ControllerClassMethodDecoratorHandlerInterface $handler): self;

    public function existHandler(ControllerClassMethodDecoratorHandlerInterface|string $handler): bool;

    public function removeHandler(ControllerClassMethodDecoratorHandlerInterface|string $handler): bool;

    public function getHandler(string $handler): ?ControllerClassMethodDecoratorHandlerInterface;

    /**
     * @return array<string, ControllerClassMethodDecoratorHandlerInterface>
     */
    public function getListHandlers(): array;
}