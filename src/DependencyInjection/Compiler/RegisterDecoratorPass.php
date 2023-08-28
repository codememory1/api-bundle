<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\AttributeHandler\Interfaces\AttributeHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds(ApiBundle::DECORATOR_HANDLER_TAG) as $id => $tags) {
            $container
                ->getDefinition(AttributeHandlerInterface::class)
                ->addMethodCall('addDecoratorHandler', [new Reference($id)]);
        }
    }
}