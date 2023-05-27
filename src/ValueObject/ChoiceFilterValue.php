<?php

namespace Codememory\ApiBundle\ValueObject;

final class ChoiceFilterValue
{
    public function __construct(
        private readonly mixed $value,
        private readonly string $label
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}