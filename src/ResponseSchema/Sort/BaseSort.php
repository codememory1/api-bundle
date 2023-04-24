<?php

namespace Codememory\ApiBundle\ResponseSchema\Sort;

use Codememory\ApiBundle\ResponseSchema\Interfaces\SortInterface;

final class BaseSort implements SortInterface
{
    public function __construct(
        private readonly string $label,
        private readonly string $key
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}