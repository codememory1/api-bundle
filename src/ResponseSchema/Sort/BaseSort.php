<?php

namespace Codememory\ApiBundle\ResponseSchema\Sort;

use Codememory\ApiBundle\ResponseSchema\Interfaces\SortInterface;

final readonly class BaseSort implements SortInterface
{
    public function __construct(
        private string $label,
        private string $key
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