<?php

namespace Codememory\ApiBundle\Factory;

use Codememory\EntityResponseControl\Configuration;
use Codememory\EntityResponseControl\Interfaces\ConfigurationFactoryInterface;
use Codememory\EntityResponseControl\Interfaces\ConfigurationInterface;
use Codememory\EntityResponseControl\Interfaces\ResponsePrototypePropertyProviderInterface;
use Codememory\EntityResponseControl\ResponseKeyNamingStrategy\ResponseKeyNamingStrategySnakeCase;

final class ERCConfigurationFactory implements ConfigurationFactoryInterface
{
    public function __construct(
        private readonly ResponseKeyNamingStrategySnakeCase $responseKeyNamingStrategySnakeCase,
        private readonly ResponsePrototypePropertyProviderInterface $responsePrototypePropertyProvider
    ) {
    }

    public function createConfiguration(): ConfigurationInterface
    {
        $configuration = new Configuration();

        $configuration->setResponseKeyNamingStrategy($this->responseKeyNamingStrategySnakeCase);
        $configuration->setResponsePrototypePropertyProvider($this->responsePrototypePropertyProvider);

        return $configuration;
    }
}