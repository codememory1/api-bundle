<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterERCObjectPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container
            ->findDefinition(ApiBundle::ERC_REFLECTOR_MANAGER_SERVICE)->setArguments([
                '$cache' => new Reference($container->getParameter(ApiBundle::ERC_CACHE_PARAMETER)),
                '$isDev' => 'prod' !== $container->getParameter('kernel.environment')
            ]);

        foreach ($container->findTaggedServiceIds(ApiBundle::ERC_PROTOTYPE_TAG) as $id => $tag) {
            $container
                ->findDefinition($id)
                ->setArguments([
                    new Reference($container->getParameter(ApiBundle::ERC_COLLECTOR_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::ERC_CONFIGURATION_FACTORY_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::ERC_EXECUTION_CONTEXT_FACTORY_PARAMETER)),
                    new Reference($container->getParameter(ApiBundle::ERC_DECORATOR_HANDLER_REGISTRAR_PARAMETER)),
                    new Reference(ApiBundle::ERC_REFLECTOR_MANAGER_SERVICE)
                ]);
        }
    }
}