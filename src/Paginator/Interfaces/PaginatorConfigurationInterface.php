<?php

namespace Codememory\ApiBundle\Paginator\Interfaces;

interface PaginatorConfigurationInterface
{
    public function getMinLimit(): int;

    public function getMaxLimit(): int;
}