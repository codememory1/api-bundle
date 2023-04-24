<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\Enum\ChoiceFilterType;
use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final class ChoiceFilter implements FilterInterface
{
    public function __construct(
        private readonly string $label,
        private readonly string $key,
        private readonly ChoiceFilterType $type = ChoiceFilterType::SINGLE,
        private readonly array $values = []
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
        return 'choice';
    }

    public function getViewSettings(): array
    {
        return [
            'type' => $this->type->name,
            'values' => $this->values
        ];
    }
}