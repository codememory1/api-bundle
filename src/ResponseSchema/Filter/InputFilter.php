<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final class InputFilter implements FilterInterface
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

    public function getKey(): string|array
    {
        return $this->key;
    }

    public function getElement(): string
    {
        return 'input';
    }

    public function getViewSettings(): array
    {
        return [];
    }
}