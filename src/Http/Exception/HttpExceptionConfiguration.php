<?php

namespace Codememory\ApiBundle\Http\Exception;

use Codememory\ApiBundle\Http\Exception\Interfaces\HttpExceptionConfigurationInterface;

class HttpExceptionConfiguration implements HttpExceptionConfigurationInterface
{
    protected array $config = [];

    public function setConfig(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getExcludedExceptions(): array
    {
        return $this->config['exclude'];
    }
}