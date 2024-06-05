<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Interfaces;

interface ResponseComponentInterface
{
    /**
     * @return array<string, ResponseSubcomponentInterface>
     */
    public function getSubcomponents(): array;

    public function getSubcomponent(string $name): ?self;

    public function removeSubcomponent(string $name): bool;

    public function addSubcomponent(ResponseSubcomponentInterface $subcomponent): static;

    public function getKey(): string;

    public function getValue(): mixed;
}