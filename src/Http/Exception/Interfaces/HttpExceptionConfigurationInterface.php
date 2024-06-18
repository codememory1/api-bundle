<?php

namespace Codememory\ApiBundle\Http\Exception\Interfaces;

interface HttpExceptionConfigurationInterface
{
    public function setConfig(array $config): static;

    /**
     * @return array<int, string>
     */
    public function getExcludedExceptions(): array;
}