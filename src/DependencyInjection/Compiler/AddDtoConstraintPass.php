<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\Dto\Constraints\ToEntityHandler;
use Codememory\Dto\Constraints\ToEntityListHandler;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddDtoConstraintPass implements CompilerPassInterface
{
    /**
     * @throws Exception
     */
    public function process(ContainerBuilder $container): void
    {
        $constraintHandlerRegisterDefinition = $container->register(ApiBundle::DTO_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID, ConstraintHandlerRegister::class);

        $container->setAlias(ConstraintHandlerRegister::class, ApiBundle::DTO_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID);

        $this->registerDefaultConstraints($container);

        foreach ($container->findTaggedServiceIds(ApiBundle::DTO_CONSTRAINT_TAG) as $id => $tags) {
            $constraintHandlerRegisterDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }

    private function registerDefaultConstraints(ContainerBuilder $container): void
    {
        $container
            ->register(ToEntityHandler::class, ToEntityHandler::class)
            ->addTag(ApiBundle::DTO_CONSTRAINT_TAG)
            ->setAutowired(true);

        $container
            ->register(ToEntityListHandler::class, ToEntityListHandler::class)
            ->addTag(ApiBundle::DTO_CONSTRAINT_TAG)
            ->setAutowired(true);
    }
}