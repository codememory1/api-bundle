<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final class RangeFilter implements FilterInterface
{
    public function __construct(
        private readonly string $label,
        private readonly string $fromKey,
        private readonly string $toKey,
        private readonly int|float $min = 0,
        private readonly int|float|null $max = null
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getKey(): string|array
    {
        return [
            'from' => $this->fromKey,
            'to' => $this->toKey
        ];
    }

    public function getElement(): string
    {
        return 'range';
    }

    public function getViewSettings(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max
        ];
    }
}