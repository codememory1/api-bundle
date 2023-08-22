<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final readonly class RangeFilter implements FilterInterface
{
    public function __construct(
        private string $label,
        private string $fromKey,
        private string $toKey,
        private int|float $min = 0,
        private int|float|null $max = null
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

    public function getExtra(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max
        ];
    }
}