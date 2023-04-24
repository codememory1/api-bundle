<?php

namespace Codememory\ApiBundle\Services\Paginator\Interfaces;

interface PaginatorOptionsInterface
{
    public function getPage(): int;

    public function getLimit(): int;

    public function getMinLimit(): int;

    public function getMaxLimit(): int;
}