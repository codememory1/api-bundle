<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Enum\CalendarFilterType;
use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;

final readonly class CalendarFilter implements FilterInterface
{
    public function __construct(
        private string $label,
        private string $key,
        private CalendarFilterType $type = CalendarFilterType::TIMESTAMP
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
        return 'calendar_input';
    }

    public function getExtra(): array
    {
        return [
            'type' => $this->type->name
        ];
    }
}