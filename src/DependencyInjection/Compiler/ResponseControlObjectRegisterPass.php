<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\Exceptions\DuplicateTaggedServiceException;
use Codememory\EntityResponseControl\ObjectDisassemblers\ObjectDisassembler;
use Codememory\EntityResponseControl\Registers\ConstraintHandlerRegister;
use Codememory\EntityResponseControl\Registers\ConstraintTypeHandlerRegister;
use Codememory\Reflection\ReflectorManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ResponseControlObjectRegisterPass implements CompilerPassInterface
{
    /**
     * @throws DuplicateTaggedServiceException
     */
    public function process(ContainerBuilder $container): void
    {
        $cacheAdapters = $container->findTaggedServiceIds(ApiBundle::RESPONSE_CONTROL_CACHE_TAG);
        $disassemblers = $container->findTaggedServiceIds(ApiBundle::RESPONSE_CONTROL_DISASSEMBLER_TAG);

        if (count($cacheAdapters) > 1) {
            throw new DuplicateTaggedServiceException(ApiBundle::RESPONSE_CONTROL_CACHE_TAG);
        }

        if (count($disassemblers) > 1) {
            throw new DuplicateTaggedServiceException(ApiBundle::RESPONSE_CONTROL_DISASSEMBLER_TAG);
        }

        if ([] === $cacheAdapters) {
            $this->registerDefaultCacheAdapter($container);
        }

        if ([] === $disassemblers) {
            $this->registerDefaultDisassembler($container);
        }

        $this->registerReflectorManager($container, array_key_first($cacheAdapters) ?: ApiBundle::RESPONSE_CONTROL_DEFAULT_CACHE_SERVICE_ID);

        foreach ($container->findTaggedServiceIds(ApiBundle::RESPONSE_CONTROL_OBJECT_TAG) as $id => $tags) {
            $container->getDefinition($id)->setArguments([
                '$objectDisassembler' => new Reference(array_key_first($disassemblers) ?: ApiBundle::RESPONSE_CONTROL_DEFAULT_DISASSEMBLER_SERVICE_ID),
                '$reflectorManager' => new Reference(ApiBundle::RESPONSE_CONTROL_REFLECTOR_MANAGER_SERVICE_ID),
                '$constraintTypeHandlerRegister' => new Reference(ConstraintTypeHandlerRegister::class),
                '$constraintHandlerRegister' => new Reference(ConstraintHandlerRegister::class)
            ]);
        }
    }

    private function registerDefaultCacheAdapter(ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::RESPONSE_CONTROL_DEFAULT_CACHE_SERVICE_ID, FilesystemAdapter::class)
            ->setArguments([
                '$namespace' => 'entity-response-control',
                '$directory' => "{$container->getParameter('kernel.cache_dir')}/codememory"
            ])
            ->addTag(ApiBundle::RESPONSE_CONTROL_CACHE_TAG);
    }

    private function registerDefaultDisassembler(ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::RESPONSE_CONTROL_DEFAULT_DISASSEMBLER_SERVICE_ID, ObjectDisassembler::class)
            ->addTag(ApiBundle::RESPONSE_CONTROL_DISASSEMBLER_TAG);
    }

    private function registerReflectorManager(ContainerBuilder $container, string $cacheAdapterId): void
    {
        $container
            ->register(ApiBundle::RESPONSE_CONTROL_REFLECTOR_MANAGER_SERVICE_ID, ReflectorManager::class)
            ->setArguments([
                '$cache' => new Reference($cacheAdapterId),
                '$isDev' => 'prod' !== $container->getParameter('kernel.environment')
            ]);
    }
}