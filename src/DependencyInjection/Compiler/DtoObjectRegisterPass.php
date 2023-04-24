<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\Exceptions\DuplicateTaggedServiceException;
use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Codememory\Reflection\ReflectorManager;
use Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class DtoObjectRegisterPass implements CompilerPassInterface
{
    /**
     * @throws Exception
     */
    public function process(ContainerBuilder $container): void
    {
        $cacheAdapters = $container->findTaggedServiceIds(ApiBundle::DTO_CACHE_TAG);
        $dtoCollectors = $container->findTaggedServiceIds(ApiBundle::DTO_COLLECTOR_TAG);

        if (count($cacheAdapters) > 1) {
            throw new DuplicateTaggedServiceException(ApiBundle::DTO_CACHE_TAG);
        }

        if (count($dtoCollectors) > 1) {
            throw new DuplicateTaggedServiceException(ApiBundle::DTO_COLLECTOR_TAG);
        }

        if ([] === $cacheAdapters) {
            $this->registerDefaultCacheAdapter($container);
        }

        if ([] === $dtoCollectors) {
            $this->registerDefaultCollector($container);
        }

        $this->registerReflectorManager($container, array_key_first($cacheAdapters) ?: ApiBundle::DTO_DEFAULT_CACHE_SERVICE_ID);

        foreach ($container->findTaggedServiceIds(ApiBundle::DTO_OBJECT_TAG) as $id => $tags) {
            $container
                ->getDefinition($id)
                ->setArguments([
                    '$collector' => new Reference(array_key_first($dtoCollectors) ?: ApiBundle::DTO_DEFAULT_COLLECTOR_SERVICE_ID),
                    '$reflectorManager' => new Reference(ApiBundle::DTO_REFLECTOR_MANAGER_SERVICE_ID),
                    '$constraintHandlerRegister' => new Reference(ConstraintHandlerRegister::class)
                ]);
        }
    }

    private function registerDefaultCacheAdapter(ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::DTO_DEFAULT_CACHE_SERVICE_ID, FilesystemAdapter::class)
            ->setArguments([
                '$namespace' => 'dto',
                '$directory' => "{$container->getParameter('kernel.cache_dir')}/codememory"
            ])
            ->addTag(ApiBundle::DTO_CACHE_TAG);
    }

    private function registerDefaultCollector(ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::DTO_DEFAULT_COLLECTOR_SERVICE_ID, BaseCollector::class)
            ->addTag(ApiBundle::DTO_COLLECTOR_TAG);
    }

    private function registerReflectorManager(ContainerBuilder $container, string $cacheAdapterId): void
    {
        $container
            ->register(ApiBundle::DTO_REFLECTOR_MANAGER_SERVICE_ID, ReflectorManager::class)
            ->setArguments([
                '$cache' => new Reference($cacheAdapterId),
                '$isDev' => 'prod' !== $container->getParameter('kernel.environment')
            ]);
    }
}