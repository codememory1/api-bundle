<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface ResponseSchemaInterface
{
    public function getHttpCode(): int;

    public function setHttpCode(int $code): self;

    public function getHeaders(): array;

    public function setHeaders(array $headers): self;

    public function getPlatformCode(): int;

    public function setPlatformCode(int $code): self;

    public function addMeta(MetaInterface $meta): self;

    public function toArray(): array;
}