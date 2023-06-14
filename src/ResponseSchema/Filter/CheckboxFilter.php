<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final class CheckboxFilter implements FilterInterface
{
    public function __construct(
        private readonly string $label,
        private readonly string $key,
        private readonly mixed $disableValue = 'false',
        private readonly mixed $enableValue = 'true'
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
        return 'checkbox';
    }

    public function getExtra(): array
    {
        return [
            'disable_value' => $this->disableValue,
            'enable_value' => $this->enableValue
        ];
    }
}