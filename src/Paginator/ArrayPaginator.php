<?php

namespace Codememory\ApiBundle\Paginator;

use function array_slice;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use function is_array;
use LogicException;

final class ArrayPaginator extends AbstractPaginator
{
    private array $paginatedValue = [];

    /**
     * @param array $value
     */
    public function setPaginatedValue(mixed $value): PaginatorInterface
    {
        if (!is_array($value)) {
            throw new LogicException(sprintf('The %s method in the %s class expects the type argument %s', __METHOD__, self::class, 'array'));
        }

        $this->paginatedValue = $value;

        return $this;
    }

    public function getTotalRecords(): int
    {
        return count($this->paginatedValue);
    }

    public function getTotalPages(): int
    {
        return ceil($this->getTotalRecords() / $this->getLimit());
    }

    public function getData(): array
    {
        return array_slice($this->paginatedValue, $this->getOffsetFrom(), $this->getLimit());
    }
}