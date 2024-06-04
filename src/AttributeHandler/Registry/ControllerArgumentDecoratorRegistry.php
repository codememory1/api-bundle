<?php

namespace Codememory\ApiBundle\AttributeHandler\Registry;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorRegistryInterface;

class ControllerArgumentDecoratorRegistry implements ControllerArgumentDecoratorRegistryInterface
{
    protected array $handlers = [];

    public function addHandler(ControllerArgumentDecoratorHandlerInterface $handler): ControllerArgumentDecoratorRegistryInterface
    {
        if (!$this->existHandler($handler)) {
            $this->handlers[$handler::class] = $handler;
        }

        return $this;
    }

    public function existHandler(ControllerArgumentDecoratorHandlerInterface|string $handler): bool
    {
        return array_key_exists($this->getHandlerKey($handler), $this->handlers);
    }

    public function removeHandler(ControllerArgumentDecoratorHandlerInterface|string $handler): bool
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

    public function getHandler(string $handler): ?ControllerArgumentDecoratorHandlerInterface
    {
        return $this->handlers[$handler] ?? null;
    }

    private function getHandlerKey(ControllerArgumentDecoratorHandlerInterface|string $handler): string
    {
        return $handler instanceof ControllerArgumentDecoratorHandlerInterface ? $handler::class : $handler;
    }
}