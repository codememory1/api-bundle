<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder;

use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var array<string, ResponseComponentInterface>
     */
    protected array $components = [];

    public function getComponents(): array
    {
        return $this->components;
    }

    public function getComponent(string $name): ?ResponseComponentInterface
    {
        return $this->components[$name] ?? null;
    }

    public function addComponent(ResponseComponentInterface $component): ResponseBuilderInterface
    {
        if (!array_key_exists($component->getKey(), $this->components)) {
            $this->components[$component->getKey()] = $component;
        }

        return $this;
    }

    public function removeComponent(string $name): ResponseBuilderInterface
    {
        if (array_key_exists($name, $this->components)) {
            unset($this->components[$name]);
        }

        return $this;
    }

    public function build(): array
    {
        return $this->doBuild($this->components);
    }

    /**
     * @param array<string, ResponseComponentInterface> $components
     */
    private function doBuild(array $components): array
    {
        $response = [];

        foreach ($components as $component) {
            $response[$component->getKey()] = $component->getValue();

            if (count($component->getSubcomponents()) > 0) {
                $response[$component->getKey()] += $this->doBuild($component->getSubcomponents());
            }
        }

        return $response;
    }
}