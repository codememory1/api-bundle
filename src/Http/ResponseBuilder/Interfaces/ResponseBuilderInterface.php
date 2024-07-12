<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Interfaces;

interface ResponseBuilderInterface
{
    public function getStatus(): int;

    public function setStatus(int $statusCode): self;

    public function getHeaders(): array;

    public function setHeaders(array $headers): self;

    /**
     * @return array<string, ResponseComponentInterface>
     */
    public function getComponents(): array;

    public function getComponent(string $name): ?ResponseComponentInterface;

    public function addComponent(ResponseComponentInterface $component): self;

    public function removeComponent(string $name): self;

    public function build(): array;
}