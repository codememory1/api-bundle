<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorOptionsInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;

final class DoctrinePaginator implements PaginatorInterface
{
    private ?Query $query = null;
    private ?Paginator $paginator = null;

    public function __construct(
        private readonly PaginatorOptionsInterface $options
    ) {
    }

    /**
     * @param Query $query
     */
    public function setQuery(mixed $query): PaginatorInterface
    {
        if (!($query instanceof Query)) {
            throw new LogicException(sprintf('The %s method in the %s class expects the type argument %s', __METHOD__, self::class, Query::class));
        }

        $this->query = $query;

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

    public function getCurrentPage(): int
    {
        $pageFromQuery = $this->options->getPage();

        if ($pageFromQuery < 1) {
            return 1;
        }

        if ($pageFromQuery > $this->getTotalPages()) {
            return $this->getTotalPages();
        }

        return $pageFromQuery;
    }

    public function getOffsetFrom(): int
    {
        $offset = ($this->getCurrentPage() * $this->getLimit()) - $this->getLimit();

        return max($offset, 0);
    }

    public function getLimit(): int
    {
        $limitFromQuery = $this->options->getLimit();

        if ($limitFromQuery < 1) {
            return 1;
        }

        if ($limitFromQuery > $this->options->getConfiguration()->getMaxLimit()) {
            return $this->options->getConfiguration()->getMaxLimit();
        }

        return $limitFromQuery;
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
            $this->paginator = new Paginator($this->query);
        }

        return $this->paginator;
    }
}