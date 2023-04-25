<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\Services\Decorator\Decorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $decoratorHandlerReferences = [];

        foreach ($container->findTaggedServiceIds(ApiBundle::DECORATOR_HANDLER_TAG) as $id => $tags) {
            $decoratorHandlerReferences[$id] = new Reference($id);
        }

        $container
            ->register(ApiBundle::DECORATOR_SERVICE_ID, Decorator::class)
            ->setArguments([
                '$decoratorHandlers' => $decoratorHandlerReferences
            ]);

        $container->setAlias(Decorator::class, ApiBundle::DECORATOR_SERVICE_ID);
    }
}