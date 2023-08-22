<?php

namespace Codememory\ApiBundle\Paginator;

use Codememory\ApiBundle\Paginator\Interfaces\PaginatorConfigurationInterface;

final readonly class PaginatorConfiguration implements PaginatorConfigurationInterface
{
    public function __construct(
        private array $config
    ) {
    }

    public function getMinLimit(): int
    {
        return $this->config['min_limit'];
    }

    public function getMaxLimit(): int
    {
        return $this->config['max_limit'];
    }
}