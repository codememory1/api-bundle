<?php

namespace Codememory\ApiBundle\DependencyInjection;

use Codememory\ApiBundle\ApiBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('codememory_api');
        $rootNode = $builder->getRootNode();

        $this->addDtoSection($rootNode);
        $this->addEntityResponseControlSection($rootNode);
        $this->addAssertSection($rootNode);
        $this->addPaginationSection($rootNode);
        $this->addThreadingSection($rootNode);
        $this->addResponseSchemaSection($rootNode);
        $this->addHttpErrorHandlerSection($rootNode);

        return $builder;
    }

    private function addDtoSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('dto')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('collector')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_COLLECTOR_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('configuration')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('factory_service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_CONFIGURATION_FACTORY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('context')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('factory_service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('decorator_handler_registrar')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('data_key_strategy')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_DATA_KEY_NAMING_STRATEGY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('dto_property_provider')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('service')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_PROPERTY_PROVIDER_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('cache')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('adapter')
                                    ->defaultValue(ApiBundle::DTO_DEFAULT_CACHE_ADAPTER_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addEntityResponseControlSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('erc')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('collector')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_COLLECTOR_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('configuration')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('factory_service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_CONFIGURATION_FACTORY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('context')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('factory_service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('decorator_handler_registrar')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('response_key_strategy')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_RESPONSE_KEY_NAMING_STRATEGY_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('prototype_property_provider')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('service')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_PROPERTY_PROVIDER_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('cache')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('adapter')
                                    ->defaultValue(ApiBundle::ERC_DEFAULT_CACHE_ADAPTER_SERVICE)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addAssertSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('assert')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('validator')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')
                                    ->cannotBeEmpty()
                                    ->defaultValue(ApiBundle::ASSERT_DEFAULT_VALIDATOR_SERVICE)
                                    ->info('Validator service')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addPaginationSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('pagination')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('options_service')
                            ->cannotBeEmpty()
                            ->defaultValue(ApiBundle::PAGINATION_DEFAULT_OPTIONS_SERVICE)
                            ->info('pagination options Service ID')
                        ->end()
                        ->scalarNode('configuration_service')
                            ->defaultValue(ApiBundle::PAGINATION_DEFAULT_CONFIGURATION_SERVICE)
                            ->info('Default Configuration Service ID')
                        ->end()
                        ->integerNode('min_limit')
                            ->defaultValue(1)
                            ->info('The minimum limit in pagination, if the value is specified less than the current one or not specified at all')
                        ->end()
                        ->integerNode('max_limit')
                            ->defaultValue(100)
                            ->info('The maximum pagination limit, if more than the maximum is specified, then the limit will be the maximum number')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addThreadingSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('threading')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('worker_options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->integerNode('delay_between_parent_process_iteration')
                                    ->defaultValue(100) // 100ms
                                    ->info('Time in milliseconds between parent process iterations')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('process_options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->integerNode('delay_between_iteration_callback_logic_call')
                                    ->defaultValue(100) // 100ms
                                    ->info('Time in milliseconds between iterations of calling the callback of the fork logic')
                                ->end()
                                ->integerNode('max_memory_usage')
                                    ->defaultValue(100 * (1024 * 1024)) // 100MB
                                    ->info('The maximum number of bytes a process can use')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addResponseSchemaSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('response_schema')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('factory_service')
                            ->cannotBeEmpty()
                            ->defaultValue(ApiBundle::RESPONSE_SCHEMA_DEFAULT_FACTORY)
                            ->info('Response Schema Factory')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addHttpErrorHandlerSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('http_error_handler')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('configuration_service')
                            ->cannotBeEmpty()
                            ->defaultValue(ApiBundle::HTTP_ERROR_HANDLER_DEFAULT_CONFIGURATION)
                            ->info('Configuration service for complete redefinition of codes')
                        ->end()
                        ->arrayNode('403')
                            ->children()
                                ->scalarNode('message')
                                    ->defaultValue('Access is denied')
                                    ->info('Message to be displayed')
                                ->end()
                                ->integerNode('platform_code')
                                    ->defaultValue(-1)
                                    ->info('Custom platform code')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('404')
                            ->children()
                                ->scalarNode('message')
                                    ->defaultValue('Page not found')
                                    ->info('Message to be displayed')
                                ->end()
                                ->integerNode('platform_code')
                                    ->defaultValue(-1)
                                    ->info('Custom platform code')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('405')
                            ->children()
                                ->scalarNode('message')
                                    ->defaultValue('Route does not support this method')
                                    ->info('Message to be displayed')
                                ->end()
                                ->integerNode('platform_code')
                                    ->defaultValue(-1)
                                    ->info('Custom platform code')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('500')
                            ->children()
                                ->scalarNode('message')
                                    ->defaultValue('Server Error')
                                    ->info('Message to be displayed')
                                ->end()
                                ->integerNode('platform_code')
                                    ->defaultValue(-1)
                                    ->info('Custom platform code')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}