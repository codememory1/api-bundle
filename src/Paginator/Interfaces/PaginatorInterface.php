<?php

namespace Codememory\ApiBundle\Paginator\Interfaces;

interface PaginatorInterface
{
    public function setValue(mixed $value): self;

    public function getPage(): int;

    public function setPage(int $page): self;

    public function getLimit(): int;

    public function setLimit(int $limit): self;

    public function getTotalRecords(): int;

    public function getTotalPages(): int;

    public function getOffsetFrom(): int;

    public function getData(): array;
}