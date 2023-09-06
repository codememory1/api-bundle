<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorConfigurationInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorOptionsInterface;
use Codememory\ApiBundle\QueryProcessor\PaginationQueryProcessor;

final readonly class PaginatorOptions implements PaginatorOptionsInterface
{
    public function __construct(
        private PaginationQueryProcessor $paginationQueryProcessor,
        private PaginatorConfigurationInterface $configuration
    ) {
    }

    public function getConfiguration(): PaginatorConfigurationInterface
    {
        return $this->configuration;
    }

    public function getPage(): int
    {
        $page = $this->paginationQueryProcessor->getPage();

        return $page <= 0 ? 1 : $page;
    }

    public function getLimit(): int
    {
        $limit = $this->paginationQueryProcessor->getLimit();

        return $limit <= 0 ? $this->getConfiguration()->getMinLimit() : $limit;
    }
}