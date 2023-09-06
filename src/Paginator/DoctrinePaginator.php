<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;

final class DoctrinePaginator extends AbstractPaginator
{
    private ?Query $paginatedValue = null;
    private ?Paginator $paginator = null;

    /**
     * @param Query $value
     */
    public function setPaginatedValue(mixed $value): PaginatorInterface
    {
        if (!($value instanceof Query)) {
            throw new LogicException(sprintf('The %s method in the %s class expects the type argument %s', __METHOD__, self::class, Query::class));
        }

        $this->paginatedValue = $value;

        return $this;
    }

    public function getTotalRecords(): int
    {
        return $this->getPaginator()->count();
    }

    public function getTotalPages(): int
    {
        return ceil($this->getPaginator()->count() / $this->getLimit());
    }

    public function getData(): array
    {
        return $this->getPaginator()->getQuery()
            ->setFirstResult($this->getOffsetFrom())
            ->setMaxResults($this->getLimit())
            ->getResult();
    }

    private function getPaginator(): Paginator
    {
        if (null === $this->paginator) {
            $this->paginator = new Paginator($this->paginatedValue);
        }

        return $this->paginator;
    }
}