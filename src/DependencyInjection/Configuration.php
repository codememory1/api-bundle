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

        $this->addDecoratorSection($rootNode);
        $this->httpSection($rootNode);
        $this->addAssertSection($rootNode);
        $this->addPaginationSection($rootNode);
        $this->addThreadingSection($rootNode);

        return $builder;
    }

    private function addDecoratorSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('decorators')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('controller_argument_value')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('registry_service')
                                    ->defaultValue(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('controller_class_method')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('registry_service')
                                    ->defaultValue(ApiBundle::CONTROLLER_CLASS_METHOD_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('controller_argument')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('registry_service')
                                    ->defaultValue(ApiBundle::CONTROLLER_ARGUMENT_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function httpSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('http')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('exception')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('config_service')
                                    ->cannotBeEmpty()
                                    ->defaultValue(ApiBundle::HTTP_EXCEPTION_DEFAULT_CONFIGURATION_SERVICE_ID)
                                    ->info('New configuration service ID')
                                ->end()
                                ->arrayNode('exclude')
                                    ->scalarPrototype()
                                        ->cannotBeEmpty()
                                        ->info('Namespace exceptions that should be ignored in processing')
                                    ->end()
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
                        ->scalarNode('validator')
                            ->cannotBeEmpty()
                            ->defaultValue(ApiBundle::ASSERT_DEFAULT_VALIDATOR_SERVICE)
                            ->info('Validator service')
                        ->end()
                        ->scalarNode('error_handler')
                            ->cannotBeEmpty()
                            ->defaultValue(ApiBundle::ASSERT_DEFAULT_ERROR_HANDLER_SERVICE)
                            ->info('Validation error handler service')
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
}