<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorConfigurationInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;

abstract class AbstractPaginator implements PaginatorInterface
{
    protected int $page = 1;
    protected int $limit = 1;

    public function __construct(
        protected readonly PaginatorConfigurationInterface $configuration
    ) {
    }

    public function getPage(): int
    {
        if ($this->page === -1 || $this->page > $this->getTotalPages()) {
            return $this->getTotalPages();
        }

        if ($this->page < 1) {
            return 1;
        }

        return $this->page;
    }

    public function setPage(int $page): PaginatorInterface
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        if ($this->limit === -1) {
            return $this->configuration->getMaxLimit();
        }

        return max($this->configuration->getMinLimit(), min($this->configuration->getMaxLimit(), $this->limit));
    }

    public function setLimit(int $limit): PaginatorInterface
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOffsetFrom(): int
    {
        $offset = ($this->getPage() * $this->getLimit()) - $this->getLimit();

        return max($offset, 0);
    }
}