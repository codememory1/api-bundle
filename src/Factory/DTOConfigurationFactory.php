<?php

namespace Codememory\ApiBundle\Factory;

use Codememory\Dto\Configuration;
use Codememory\Dto\Interfaces\ConfigurationFactoryInterface;
use Codememory\Dto\Interfaces\ConfigurationInterface;
use Codememory\Dto\Interfaces\DataKeyNamingStrategyInterface;
use Codememory\Dto\Interfaces\DataTransferObjectPropertyProviderInterface;

final readonly class DTOConfigurationFactory implements ConfigurationFactoryInterface
{
    public function __construct(
        private DataKeyNamingStrategyInterface $dataKeyNamingStrategy,
        private DataTransferObjectPropertyProviderInterface $dataTransferObjectPropertyProvider
    ) {
    }

    public function createConfiguration(): ConfigurationInterface
    {
        $configuration = new Configuration();

        $configuration->setDataKeyNamingStrategy($this->dataKeyNamingStrategy);
        $configuration->setDataTransferObjectPropertyProvider($this->dataTransferObjectPropertyProvider);

        return $configuration;
    }
}