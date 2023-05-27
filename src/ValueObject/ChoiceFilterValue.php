<?php

namespace Codememory\ApiBundle\ValueObject;

final class ChoiceFilterValue
{
    public function __construct(
        private readonly string $key,
        private readonly string $label
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}