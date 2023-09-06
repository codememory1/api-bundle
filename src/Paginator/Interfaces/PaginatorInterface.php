<?php

namespace Codememory\ApiBundle\Paginator\Interfaces;

interface PaginatorInterface
{
    public function setPaginatedValue(mixed $value): self;

    public function getTotalRecords(): int;

    public function getTotalPages(): int;

    public function getCurrentPage(): int;

    public function getOffsetFrom(): int;

    public function getLimit(): int;

    public function getData(): array;
}