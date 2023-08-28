<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDTODecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds(ApiBundle::DTO_DECORATOR_TAG) as $id => $tag) {
            $container
                ->findDefinition($container->getParameter(ApiBundle::DTO_DECORATOR_HANDLER_REGISTRAR_PARAMETER))
                ->addMethodCall('register', [new Reference($id)]);
        }
    }
}