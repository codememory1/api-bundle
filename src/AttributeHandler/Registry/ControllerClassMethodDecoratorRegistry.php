<?php

namespace Codememory\ApiBundle\AttributeHandler\Registry;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerClassMethodDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerClassMethodDecoratorRegistryInterface;

class ControllerClassMethodDecoratorRegistry implements ControllerClassMethodDecoratorRegistryInterface
{
    protected array $handlers = [];

    public function addHandler(ControllerClassMethodDecoratorHandlerInterface $handler): ControllerClassMethodDecoratorRegistryInterface
    {
        if (!$this->existHandler($handler)) {
            $this->handlers[$handler::class] = $handler;
        }

        return $this;
    }

    public function existHandler(ControllerClassMethodDecoratorHandlerInterface|string $handler): bool
    {
        return array_key_exists($this->getHandlerKey($handler), $this->handlers);
    }

    public function removeHandler(ControllerClassMethodDecoratorHandlerInterface|string $handler): bool
    {
        if ($this->existHandler($handler)) {
            unset($this->handlers[$this->getHandlerKey($handler)]);

            return true;
        }

        return false;
    }

    public function getListHandlers(): array
    {
        return $this->handlers;
    }

    public function getHandler(string $handler): ?ControllerClassMethodDecoratorHandlerInterface
    {
        return $this->handlers[$handler] ?? null;
    }

    private function getHandlerKey(ControllerClassMethodDecoratorHandlerInterface|string $handler): string
    {
        return $handler instanceof ControllerClassMethodDecoratorHandlerInterface ? $handler::class : $handler;
    }
}