<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorOptionsInterface;

abstract class AbstractPaginator implements PaginatorInterface
{
    public function __construct(
        protected readonly PaginatorOptionsInterface $options
    ) {
    }

    public function getCurrentPage(): int
    {
        $pageFromQuery = $this->options->getPage();

        if ($pageFromQuery === -1) {
            return $this->getTotalPages();
        }

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
}