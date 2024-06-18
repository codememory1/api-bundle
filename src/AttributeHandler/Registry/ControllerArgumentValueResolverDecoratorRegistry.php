<?php

namespace Codememory\ApiBundle\AttributeHandler\Registry;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorRegistryInterface;

class ControllerArgumentValueResolverDecoratorRegistry implements ControllerArgumentValueResolverDecoratorRegistryInterface
{
    protected array $handlers = [];

    public function addHandler(ControllerArgumentValueResolverDecoratorHandlerInterface $handler): ControllerArgumentValueResolverDecoratorRegistryInterface
    {
        if (!$this->existHandler($handler)) {
            $this->handlers[$handler::class] = $handler;
        }

        return $this;
    }

    public function existHandler(ControllerArgumentValueResolverDecoratorHandlerInterface|string $handler): bool
    {
        return array_key_exists($this->getHandlerKey($handler), $this->handlers);
    }

    public function removeHandler(ControllerArgumentValueResolverDecoratorHandlerInterface|string $handler): bool
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

    public function getHandler(string $handler): ?ControllerArgumentValueResolverDecoratorHandlerInterface
    {
        return $this->handlers[$handler] ?? null;
    }

    private function getHandlerKey(ControllerArgumentValueResolverDecoratorHandlerInterface|string $handler): string
    {
        return $handler instanceof ControllerArgumentValueResolverDecoratorHandlerInterface ? $handler::class : $handler;
    }
}