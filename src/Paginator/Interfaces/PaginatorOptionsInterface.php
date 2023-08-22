<?php

namespace Codememory\ApiBundle\Paginator\Interfaces;

interface PaginatorOptionsInterface
{
    public function getConfiguration(): PaginatorConfigurationInterface;

    public function getPage(): int;

    public function getLimit(): int;
}