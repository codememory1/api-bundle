<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Interfaces;

interface ResponseBuilderInterface
{
    /**
     * @return array<string, ResponseComponentInterface>
     */
    public function getComponents(): array;

    public function getComponent(string $name): ?ResponseComponentInterface;

    public function addComponent(ResponseComponentInterface $component): self;

    public function removeComponent(string $name): self;

    public function build(): array;
}