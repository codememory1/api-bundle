<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\EntityResponseControl\Registers\ConstraintHandlerRegister;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddResponseControlConstraintPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $constraintHandlerRegisterDefinition = $container->register(ApiBundle::RESPONSE_CONTROL_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID, ConstraintHandlerRegister::class);

        $container->setAlias(ConstraintHandlerRegister::class, ApiBundle::RESPONSE_CONTROL_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID);

        foreach ($container->findTaggedServiceIds(ApiBundle::RESPONSE_CONTROL_CONSTRAINT_TAG) as $id => $tags) {
            $constraintHandlerRegisterDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }
}