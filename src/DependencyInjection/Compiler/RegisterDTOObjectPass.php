<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDTOObjectPass implements CompilerPassInterface
{
    /**
     * @throws Exception
     */
    public function process(ContainerBuilder $container): void
    {
        $container
            ->findDefinition(ApiBundle::DTO_REFLECTOR_MANAGER_SERVICE)
            ->setArguments([
                '$cache' => new Reference($container->getParameter(ApiBundle::DTO_CACHE_PARAMETER)),
                '$isDev' => 'prod' !== $container->getParameter('kernel.environment')
            ]);

        foreach ($container->findTaggedServiceIds(ApiBundle::DTO_OBJECT_TAG) as $id => $tags) {
            $container
                ->getDefinition($id)
                ->setArguments([
                    new Reference($container->getParameter(ApiBundle::DTO_COLLECTOR_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::DTO_CONFIGURATION_FACTORY_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::DTO_EXECUTION_CONTEXT_FACTORY_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::DTO_DECORATOR_HANDLER_REGISTRAR_PARAMETER)),
                    new Reference(ApiBundle::DTO_REFLECTOR_MANAGER_SERVICE)
                ]);
        }
    }
}