<?php

namespace Codememory\ApiBundle;

use Codememory\ApiBundle\DependencyInjection\ApiExtension;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterControllerArgumentDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterControllerArgumentValueResolverDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterControllerClassMethodDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDTODecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDTOObjectPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterERCDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterERCObjectPass;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ApiBundle extends Bundle
{
    // DTO Services
    public const DTO_DEFAULT_COLLECTOR_SERVICE = 'codememory.dto.default_collector';
    public const DTO_DEFAULT_CONFIGURATION_FACTORY_SERVICE = 'codememory.dto.default_configuration_factory';
    public const DTO_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE = 'codememory.dto.default_execution_context_factory';
    public const DTO_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE = 'codememory.dto.default_decorator_handler_registrar';
    public const DTO_DEFAULT_DATA_KEY_NAMING_STRATEGY_SERVICE = 'codememory.dto.default_data_key_naming_strategy';
    public const DTO_DEFAULT_PROPERTY_PROVIDER_SERVICE = 'codememory.dto.default_property_provider';
    public const DTO_DEFAULT_CACHE_ADAPTER_SERVICE = 'codememory.dto.default_cache_adapter';
    public const DTO_REFLECTOR_MANAGER_SERVICE = 'codememory.dto.reflector_manager';

    // DTO Tags
    public const DTO_OBJECT_TAG = 'codememory.dto.object';
    public const DTO_DECORATOR_TAG = 'codememory.dto.decorator';

    // DTO Parameters
    public const DTO_COLLECTOR_PARAMETER = 'codememory.dto.collector';
    public const DTO_CONFIGURATION_FACTORY_PARAMETER = 'codememory.dto.configuration_factory';
    public const DTO_EXECUTION_CONTEXT_FACTORY_PARAMETER = 'codememory.dto.execution_context_factory';
    public const DTO_CACHE_PARAMETER = 'codememory.dto.cache';
    public const DTO_DATA_KEY_NAMING_STRATEGY_PARAMETER = 'codememory.dto.data_key_naming_strategy';
    public const DTO_PROPERTY_PROVIDER_PARAMETER = 'codememory.dto.property_provider';
    public const DTO_DECORATOR_HANDLER_REGISTRAR_PARAMETER = 'codememory.dto.decorator_handler_registrar';

    // EntityResponseControl Services
    public const ERC_DEFAULT_COLLECTOR_SERVICE = 'codememory.erc.default_collector';
    public const ERC_DEFAULT_CONFIGURATION_FACTORY_SERVICE = 'codememory.erc.default_configuration_factory';
    public const ERC_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE = 'codememory.erc.default_execution_context_factory';
    public const ERC_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE = 'codememory.erc.default_decorator_handler_registrar';
    public const ERC_DEFAULT_RESPONSE_KEY_NAMING_STRATEGY_SERVICE = 'codememory.erc.default_response_key_naming_strategy';
    public const ERC_DEFAULT_PROPERTY_PROVIDER_SERVICE = 'codememory.erc.default_property_provider';
    public const ERC_DEFAULT_CACHE_ADAPTER_SERVICE = 'codememory.erc.default_cache_adapter';
    public const ERC_REFLECTOR_MANAGER_SERVICE = 'codememory.erc.reflector_manager';

    // EntityResponseControl Tags
    public const ERC_PROTOTYPE_TAG = 'codememory.erc.prototype';
    public const ERC_DECORATOR_TAG = 'codememory.erc.decorator';

    // EntityResponseControl Parameters
    public const ERC_COLLECTOR_PARAMETER = 'codememory.erc.collector';
    public const ERC_CONFIGURATION_FACTORY_PARAMETER = 'codememory.erc.configuration_factory';
    public const ERC_EXECUTION_CONTEXT_FACTORY_PARAMETER = 'codememory.erc.execution_context_factory';
    public const ERC_CACHE_PARAMETER = 'codememory.erc.cache';
    public const ERC_RESPONSE_KEY_NAMING_STRATEGY_PARAMETER = 'codememory.erc.response_key_naming_strategy';
    public const ERC_PROPERTY_PROVIDER_PARAMETER = 'codememory.erc.property_provider';
    public const ERC_DECORATOR_HANDLER_REGISTRAR_PARAMETER = 'codememory.erc.decorator_handler_registrar';

    // Paginator Services
    public const PAGINATION_DEFAULT_CONFIGURATION_SERVICE = 'codememory.pagination.default_configuration';
    public const PAGINATION_DEFAULT_OPTIONS_SERVICE = 'codememory.pagination.default_options';
    public const PAGINATION_DEFAULT_PAGINATOR = 'codememory.pagination.default_paginator';

    // Assert
    public const ASSERT_DEFAULT_VALIDATOR_SERVICE = 'codememory.assert.default_validator';
    public const ASSERT_DEFAULT_ERROR_HANDLER_SERVICE = 'codememory.assert.default_error_handler';
    public const ASSERT_ERROR_HANDLER_SERVICE = 'codememory.assert.error_handler';
    public const ASSERT_ERROR_HANDLER_PARAMETER = 'codememory.assert.error_handler';

    // Others
    public const WORKER_OPTIONS_SERVICE_ID = 'codememory.multithreading.worker_options';
    public const PROCESS_OPTIONS_SERVICE_ID = 'codememory.multithreading.process_options';
    public const PROCESS_MANAGER_SERVICE_ID = 'codememory.multithreading.process_manager';
    public const JSON_SCHEMA_VALIDATOR_SERVICE_ID = 'codememory.validator.json';

    // Decorators
    public const CONTROLLER_ARGUMENT_VALUE_RESOLVER_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_argument_value_resolver';
    public const CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_argument_value_resolver';
    public const CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_TAG = 'codememory.decorator.controller_argument_value_resolver';
    public const CONTROLLER_CLASS_METHOD_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_class_method';
    public const CONTROLLER_CLASS_METHOD_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_class_method';
    public const CONTROLLER_CLASS_METHOD_DECORATOR_TAG = 'codememory.decorator.controller_class_method';
    public const CONTROLLER_ARGUMENT_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID = 'codememory.decorator.registry.default_controller_argument';
    public const CONTROLLER_ARGUMENT_DECORATOR_REGISTRY_SERVICE_PARAMETER = 'codememory.decorator.registry.controller_argument';
    public const CONTROLLER_ARGUMENT_DECORATOR_TAG = 'codememory.decorator.controller_argument';
    public const HTTP_EXCEPTION_DEFAULT_CONFIGURATION_SERVICE_ID = 'codememory.http_exception.default_configuration';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDTODecoratorPass());
        $container->addCompilerPass(new RegisterDTOObjectPass());
        $container->addCompilerPass(new RegisterERCDecoratorPass());
        $container->addCompilerPass(new RegisterERCObjectPass());
        $container->addCompilerPass(new RegisterControllerArgumentValueResolverDecoratorPass());
        $container->addCompilerPass(new RegisterControllerClassMethodDecoratorPass());
        $container->addCompilerPass(new RegisterControllerArgumentDecoratorPass());
    }

    #[Pure]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ApiExtension();
    }
}