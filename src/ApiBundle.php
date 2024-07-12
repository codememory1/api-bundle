<?php

namespace Codememory\ApiBundle;

use Codememory\ApiBundle\DependencyInjection\ApiExtension;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDecoratorPass;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ApiBundle extends Bundle
{
    // Paginator Services
    public const string PAGINATION_DEFAULT_CONFIGURATION_SERVICE = 'codememory.pagination.default_configuration';
    public const string PAGINATION_DEFAULT_OPTIONS_SERVICE = 'codememory.pagination.default_options';
    public const string PAGINATION_DEFAULT_PAGINATOR = 'codememory.pagination.default_paginator';

    // Assert
    public const string ASSERT_DEFAULT_VALIDATOR_SERVICE = 'codememory.assert.default_validator';
    public const string ASSERT_DEFAULT_ERROR_HANDLER_SERVICE = 'codememory.assert.default_error_handler';

    // Others
    public const string WORKER_OPTIONS_SERVICE_ID = 'codememory.multithreading.worker_options';
    public const string PROCESS_OPTIONS_SERVICE_ID = 'codememory.multithreading.process_options';
    public const string PROCESS_MANAGER_SERVICE_ID = 'codememory.multithreading.process_manager';
    public const string JSON_SCHEMA_VALIDATOR_SERVICE_ID = 'codememory.validator.json';

    // Decorators
    public const string CONTROLLER_ARGUMENT_VALUE_RESOLVER_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_argument_value_resolver';
    public const string CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_argument_value_resolver';
    public const string CONTROLLER_CLASS_METHOD_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_class_method';
    public const string CONTROLLER_CLASS_METHOD_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_class_method';
    public const string CONTROLLER_ARGUMENT_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_argument';
    public const string CONTROLLER_ARGUMENT_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_argument';
    public const string HTTP_EXCEPTION_DEFAULT_CONFIGURATION_SERVICE_ID = 'codememory.http_exception.default_configuration';
    public const string DECORATOR_TAG = 'codememory.decorator';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDecoratorPass());
    }

    #[Pure]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ApiExtension();
    }
}