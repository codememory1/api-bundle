<?php

namespace Codememory\ApiBundle\Paginator;

use function array_slice;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use function is_array;
use LogicException;

final class ArrayPaginator extends AbstractPaginator
{
    private array $value = [];

    /**
     * @param array $value
     */
    public function setValue(mixed $value): PaginatorInterface
    {
        if (!is_array($value)) {
            throw new LogicException(sprintf('The "%s" method in the "%s" class expects the type argument "%s"', __METHOD__, self::class, 'array'));
        }

        $this->value = $value;

        return $this;
    }

    public function getTotalRecords(): int
    {
        return count($this->value);
    }

    public function getTotalPages(): int
    {
        return ceil($this->getTotalRecords() / $this->getLimit());
    }

    public function getData(): array
    {
        return array_slice($this->value, $this->getOffsetFrom(), $this->getLimit());
    }
}