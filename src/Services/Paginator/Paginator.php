<?php

namespace Codememory\ApiBundle\Services\Paginator;

use Codememory\ApiBundle\Services\Paginator\Interfaces\PaginatorOptionsInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use LogicException;

class Paginator
{
    private ?Query $query = null;
    private ?DoctrinePaginator $paginator = null;

    public function __construct(
        private readonly PaginatorOptionsInterface $options
    ) {
    }

    public function setQuery(Query $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getPaginator(): DoctrinePaginator
    {
        if (null === $this->paginator) {
            if (null === $this->query) {
                throw new LogicException(sprintf('Paginator expects Query call setQuery method on %s', self::class));
            }

            $this->paginator = new DoctrinePaginator($this->query);
        }

        return $this->paginator;
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

        if ($limitFromQuery > $this->options->getMaxLimit()) {
            return $this->options->getMaxLimit();
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
}