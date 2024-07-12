<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder;

use Codememory\ApiBundle\Event\ResponsePreBuildEvent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    protected int $status = 200;

    protected array $headers = [];

    /**
     * @var array<string, ResponseComponentInterface>
     */
    protected array $components = [];

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param array<string, ResponseComponentInterface> $components
     */
    protected function doBuild(array $components): array
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $statusCode): ResponseBuilderInterface
    {
        $this->status = $statusCode;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): ResponseBuilderInterface
    {
        $this->headers = $headers;

        return $this;
    }

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
        $this->eventDispatcher->dispatch(new ResponsePreBuildEvent($this), ResponsePreBuildEvent::NAME);

        return $this->doBuild($this->components);
    }
}