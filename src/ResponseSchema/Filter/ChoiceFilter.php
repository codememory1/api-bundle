<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\Enum\ChoiceFilterType;
use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;
use Codememory\ApiBundle\ValueObject\ChoiceFilterValue;

final class ChoiceFilter implements FilterInterface
{
    /**
     * @param array<int, ChoiceFilterValue> $values
     */
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

    public function getExtra(): array
    {
        return [
            'type' => $this->type->name,
            'values' => array_map(static function (ChoiceFilterValue $value) {
                return [
                    'value' => $value->getValue(),
                    'label' => $value->getLabel()
                ];
            }, $this->values)
        ];
    }
}