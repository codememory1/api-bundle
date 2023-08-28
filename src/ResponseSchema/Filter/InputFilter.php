<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final readonly class InputFilter implements FilterInterface
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

    public function getKey(): string|array
    {
        return $this->key;
    }

    public function getElement(): string
    {
        return 'input';
    }

    public function getExtra(): array
    {
        return [];
    }
}