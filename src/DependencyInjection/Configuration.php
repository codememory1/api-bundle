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

        $this->addAssertSection($rootNode);
        $this->addPaginationSection($rootNode);
        $this->addThreadingSection($rootNode);

        return $builder;
    }

    private function addAssertSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('assert')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('default_http_code')
                            ->defaultValue(400)
                            ->info('Default HTTP code to be used if no code is passed in the payload constraint')
                        ->end()
                        ->integerNode('default_platform_code')
                            ->defaultValue(0)
                            ->info('Platform default code')
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
                            ->defaultValue(ApiBundle::PAGINATION_DEFAULT_OPTIONS_SERVICE_ID)
                            ->info('pagination options service ID')
                        ->end()
                        ->integerNode('min_limit')
                            ->defaultValue(10)
                            ->info('The minimum limit in pagination, if the value is specified less than the current one or not specified at all')
                        ->end()
                        ->integerNode('max_limit')
                            ->defaultValue(50)
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