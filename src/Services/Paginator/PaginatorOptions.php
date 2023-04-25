<?php

namespace Codememory\ApiBundle\Services\Paginator;

use Codememory\ApiBundle\Services\Paginator\Interfaces\PaginatorOptionsInterface;
use Codememory\ApiBundle\Services\QueryProcessor\PaginationQueryProcessor;

final class PaginatorOptions implements PaginatorOptionsInterface
{
    public function __construct(
        private readonly PaginationQueryProcessor $paginationQueryProcessor,
        private readonly int $minLimit,
        private readonly int $maxLimit,
    ) {
    }

    public function getPage(): int
    {
        $page = $this->paginationQueryProcessor->getPage();

        return $page <= 0 ? 1 : $page;
    }

    public function getLimit(): int
    {
        $limit = $this->paginationQueryProcessor->getLimit();

        return $limit <= 0 ? $this->minLimit : $limit;
    }

    public function getMinLimit(): int
    {
        return $this->minLimit;
    }

    public function getMaxLimit(): int
    {
        return $this->maxLimit;
    }
}