<?php

namespace Codememory\ApiBundle;

use Codememory\ApiBundle\DependencyInjection\ApiExtension;
use Codememory\ApiBundle\DependencyInjection\Compiler\AddJWTAdapterPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDTODecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDTOObjectPass;
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
    public const ERC_DEFAULT_COLLECTOR_SERVICE = 'codememory.entity_response_control.default_collector';
    public const ERC_DEFAULT_CONFIGURATION_FACTORY_SERVICE = 'codememory.entity_response_control.default_configuration_factory';
    public const ERC_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE = 'codememory.entity_response_control.default_execution_context_factory';
    public const ERC_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE = 'codememory.entity_response_control.default_decorator_handler_registrar';
    public const ERC_DEFAULT_RESPONSE_KEY_NAMING_STRATEGY_SERVICE = 'codememory.entity_response_control.default_response_key_naming_strategy';
    public const ERC_DEFAULT_PROPERTY_PROVIDER_SERVICE = 'codememory.entity_response_control.default_property_provider';
    public const ERC_DEFAULT_CACHE_ADAPTER_SERVICE = 'codememory.entity_response_control.default_cache_adapter';
    public const ERC_REFLECTOR_MANAGER_SERVICE = 'codememory.entity_response_control.reflector_manager';

    // EntityResponseControl Tags
    public const ERC_PROTOTYPE_TAG = 'codememory.entity_response_control.prototype';
    public const ERC_DECORATOR_TAG = 'codememory.entity_response_control.decorator';

    // EntityResponseControl Parameters
    public const ERC_COLLECTOR_PARAMETER = 'codememory.entity_response_control.collector';
    public const ERC_CONFIGURATION_FACTORY_PARAMETER = 'codememory.entity_response_control.configuration_factory';
    public const ERC_EXECUTION_CONTEXT_FACTORY_PARAMETER = 'codememory.entity_response_control.execution_context_factory';
    public const ERC_CACHE_PARAMETER = 'codememory.entity_response_control.cache';
    public const ERC_RESPONSE_KEY_NAMING_STRATEGY_PARAMETER = 'codememory.entity_response_control.response_key_naming_strategy';
    public const ERC_PROPERTY_PROVIDER_PARAMETER = 'codememory.entity_response_control.property_provider';
    public const ERC_DECORATOR_HANDLER_REGISTRAR_PARAMETER = 'codememory.entity_response_control.decorator_handler_registrar';

    // Others
    public const WORKER_OPTIONS_SERVICE_ID = 'codememory.multithreading.worker_options';
    public const PROCESS_OPTIONS_SERVICE_ID = 'codememory.multithreading.process_options';
    public const PROCESS_MANAGER_SERVICE_ID = 'codememory.multithreading.process_manager';
    public const JSON_SCHEMA_VALIDATOR_SERVICE_ID = 'codememory.validator.json';
    public const ASSERT_VALIDATOR_SERVICE_ID = 'codememory.validator.assert';
    public const PAGINATION_DEFAULT_OPTIONS_SERVICE_ID = 'codememory.pagination.default_options';
    public const PAGINATION_MIN_LIMIT_PARAMETER = 'codememory.pagination.min_limit';
    public const PAGINATION_MAX_LIMIT_PARAMETER = 'codememory.pagination.max_limit';
    public const DECORATOR_SERVICE_ID = 'codememory.decorator';
    public const DECORATOR_HANDLER_TAG = 'codememory.decorator.handler';
    public const JWT_ADAPTER_TAG = 'codememory.jwt.adapter';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDTODecoratorPass());
        $container->addCompilerPass(new RegisterDTOObjectPass());
        $container->addCompilerPass(new RegisterDecoratorPass());
        $container->addCompilerPass(new AddJWTAdapterPass());
    }

    #[Pure]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ApiExtension();
    }
}