<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final readonly class CheckboxFilter implements FilterInterface
{
    public function __construct(
        private string $label,
        private string $key,
        private mixed $disableValue = 'false',
        private mixed $enableValue = 'true'
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