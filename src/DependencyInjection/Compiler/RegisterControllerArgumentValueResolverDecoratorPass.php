<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterControllerArgumentValueResolverDecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_TAG) as $id => $tags) {
            $container
                ->getDefinition($container->getParameter(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_REGISTRY_SERVICE_PARAMETER))
                ->addMethodCall('addHandler', [new Reference($id)]);
        }
    }
}