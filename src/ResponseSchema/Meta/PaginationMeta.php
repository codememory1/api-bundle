<?php

namespace Codememory\ApiBundle\ResponseSchema\Meta;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\MetaInterface;

final readonly class PaginationMeta implements MetaInterface
{
    public function __construct(
        private PaginatorInterface $paginator
    ) {
    }

    public function getKey(): string
    {
        return 'pagination';
    }

    public function toArray(): array
    {
        return [
            'total_pages' => $this->paginator->getTotalPages(),
            'current_page' => $this->paginator->getCurrentPage(),
            'limit' => $this->paginator->getLimit(),
            'total_records' => $this->paginator->getTotalRecords()
        ];
    }
}