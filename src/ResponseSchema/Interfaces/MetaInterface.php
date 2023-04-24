<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface MetaInterface
{
    public function getKey(): string;

    public function toArray(): array;
}