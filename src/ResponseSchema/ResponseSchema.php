<?php

namespace Codememory\ApiBundle\ResponseSchema;

use Codememory\ApiBundle\ResponseSchema\Interfaces\MetaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;

class ResponseSchema implements ResponseSchemaInterface
{
    private int $httpCode = 200;
    private array $headers = [];
    private int $platformCode = 0;
    private ?ViewInterface $view = null;
    private array $meta = [];

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $code): self
    {
        $this->httpCode = $code;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): ResponseSchemaInterface
    {
        $this->headers = $headers;

        return $this;
    }

    public function getPlatformCode(): int
    {
        return $this->platformCode;
    }

    public function setPlatformCode(int $code): self
    {
        $this->platformCode = $code;

        return $this;
    }

    public function getView(): ?ViewInterface
    {
        return $this->view;
    }

    public function setView(ViewInterface $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function addMeta(MetaInterface $meta): self
    {
        $this->meta[$meta->getKey()] = $meta->toArray();

        return $this;
    }

    public function toArray(): array
    {
        $response = [
            'platform_code' => $this->platformCode,
            'is_error' => $this->view?->isError(),
            'view' => $this->view?->toArray() ?: []
        ];

        if ([] !== $this->meta) {
            $response['meta'] = $this->meta;
        }

        return $response;
    }
}