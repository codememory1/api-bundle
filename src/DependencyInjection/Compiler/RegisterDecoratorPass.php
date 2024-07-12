<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDecoratorPass implements CompilerPassInterface
{
    public const string CONTROLLER_CLASS_METHOD_TYPE = 'controller_class_method';
    public const string CONTROLLER_ARGUMENT_TYPE = 'controller_argument';
    public const string CONTROLLER_ARGUMENT_VALUE_RESOLVER_TYPE = 'controller_argument_value_resolver';
    public const array ALLOWED_TYPES = [
        self::CONTROLLER_CLASS_METHOD_TYPE,
        self::CONTROLLER_ARGUMENT_TYPE,
        self::CONTROLLER_ARGUMENT_VALUE_RESOLVER_TYPE
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds(ApiBundle::DECORATOR_TAG) as $id => $tags) {
            $tag = $tags[0];

            $this->throwIfTypeIsUndefined($tag);
            $this->throwIfInvalidType($tag);

            switch ($tag['type']) {
                case self::CONTROLLER_CLASS_METHOD_TYPE:
                    $this->controllerClassMethodDecoratorTypeHandler($container, $id);
                    break;
                case self::CONTROLLER_ARGUMENT_TYPE:
                    $this->controllerArgumentDecoratorTypeHandler($container, $id);
                    break;
                case self::CONTROLLER_ARGUMENT_VALUE_RESOLVER_TYPE:
                    $this->controllerArgumentValueResolverDecoratorTypeHandler($container, $id);
                    break;
            }
        }
    }

    private function controllerClassMethodDecoratorTypeHandler(ContainerBuilder $container, string $serviceId): void
    {
        $container
            ->findDefinition($container->getParameter(ApiBundle::CONTROLLER_CLASS_METHOD_DECORATOR_REGISTRY_SERVICE_PARAMETER))
            ->addMethodCall('addHandler', [new Reference($serviceId)]);
    }

    private function controllerArgumentDecoratorTypeHandler(ContainerBuilder $container, string $serviceId): void
    {
        $container
            ->getDefinition($container->getParameter(ApiBundle::CONTROLLER_ARGUMENT_DECORATOR_REGISTRY_SERVICE_PARAMETER))
            ->addMethodCall('addHandler', [new Reference($serviceId)]);
    }

    private function controllerArgumentValueResolverDecoratorTypeHandler(ContainerBuilder $container, string $serviceId): void
    {
        $container
            ->getDefinition($container->getParameter(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_REGISTRY_SERVICE_PARAMETER))
            ->addMethodCall('addHandler', [new Reference($serviceId)]);
    }

    private function throwIfTypeIsUndefined(array $tag): void
    {
        if (!array_key_exists('type', $tag)) {
            throw new LogicException(sprintf('The "%s" tag expects a decorator type with the key "type"', ApiBundle::DECORATOR_TAG));
        }
    }

    private function throwIfInvalidType(array $tag): void
    {
        if (!in_array($tag['type'], self::ALLOWED_TYPES)) {
            throw new LogicException(sprintf(
                'Decorator type "%s" is not available. Available types: "%s"',
                $tag['type'],
                implode(', ', self::ALLOWED_TYPES)
            ));
        }
    }
}