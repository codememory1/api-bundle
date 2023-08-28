<?php

namespace Codememory\ApiBundle\ResponseSchema\Meta;

use Codememory\ApiBundle\ResponseSchema\Interfaces\FilterInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\MetaInterface;

final readonly class FilterMeta implements MetaInterface
{
    /**
     * @param array<int, FilterInterface> $filters
     */
    public function __construct(
        private array $filters
    ) {
    }

    public function getKey(): string
    {
        return 'filters';
    }

    public function toArray(): array
    {
        return array_map(static function(FilterInterface $filter) {
            return [
                'key' => $filter->getKey(),
                'label' => $filter->getLabel(),
                'element' => $filter->getElement(),
                'extra' => $filter->getExtra()
            ];
        }, $this->filters);
    }
}