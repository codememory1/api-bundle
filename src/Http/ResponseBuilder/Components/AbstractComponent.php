<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components;

use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;

abstract class AbstractComponent implements ResponseComponentInterface
{
    /**
     * @var array<string, ResponseSubcomponentInterface>
     */
    protected array $subcomponents = [];

    public function getSubcomponents(): array
    {
        return $this->subcomponents;
    }

    public function getSubcomponent(string $name): ?ResponseComponentInterface
    {
        return $this->subcomponents[$name] ?? null;
    }

    public function removeSubcomponent(string $name): bool
    {
        if (array_key_exists($name, $this->subcomponents)) {
            unset($this->subcomponents[$name]);

            return true;
        }

        return false;
    }

    public function addSubcomponent(ResponseSubcomponentInterface $subcomponent): static
    {
        if (!array_key_exists($subcomponent->getKey(), $this->subcomponents)) {
            $this->subcomponents[$subcomponent->getKey()] = $subcomponent;
        }

        return $this;
    }
}