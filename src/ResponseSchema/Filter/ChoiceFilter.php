<?php

namespace Codememory\ApiBundle\ResponseSchema\Filter;

use Codememory\ApiBundle\ResponseSchema\Enum\ChoiceFilterType;
use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;
use Codememory\ApiBundle\ValueObject\ChoiceFilterValue;

final readonly class ChoiceFilter implements FilterInterface
{
    /**
     * @param array<int, ChoiceFilterValue> $values
     */
    public function __construct(
        private string $label,
        private string $key,
        private ChoiceFilterType $type = ChoiceFilterType::SINGLE,
        private array $values = []
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
            'values' => array_map(static function(ChoiceFilterValue $value) {
                return [
                    'value' => $value->getValue(),
                    'label' => $value->getLabel()
                ];
            }, $this->values)
        ];
    }
}