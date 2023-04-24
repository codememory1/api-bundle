<?php

namespace Codememory\ApiBundle\Services;

use Codememory\ApiBundle\Services\Filter\Interfaces\FilterInterface;
use Doctrine\ORM\QueryBuilder;

final class ChoiceFilter implements FilterInterface
{
    public function __construct(
        private readonly string $label,
        private readonly array $options,
        private readonly array $allowedFilters
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getElementType(): string
    {
        return 'choice';
    }

    public function getElements(): array
    {
        return [
            'key' => $this->options['property'],
            'values' => $this->options['allowed_values']
        ];
    }

    public function buildQueryBuilder(QueryBuilder $qb, string $alias): QueryBuilder
    {
        $property = $this->options['property'];

        if (array_key_exists($property, $this->allowedFilters) && array_key_exists($this->allowedFilters[$property], $this->options['allowed_values'])) {
            $qb->andWhere(
                $qb->expr()->eq("{$alias}.{$property}", ":_filter_choice_{$property}")
            );

            $qb->setParameter(":_filter_choice_{$property}", $this->allowedFilters[$property]);
        }

        return $qb;
    }
}