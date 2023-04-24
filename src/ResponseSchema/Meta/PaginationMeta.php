<?php

namespace Codememory\ApiBundle\ResponseSchema\Meta;

use Codememory\ApiBundle\ResponseSchema\Interfaces\MetaInterface;
use Codememory\ApiBundle\Services\Paginator\Paginator;

final class PaginationMeta implements MetaInterface
{
    public function __construct(
        private readonly Paginator $paginator
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
            'limit' => $this->paginator->getLimit()
        ];
    }
}