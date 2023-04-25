<?php

namespace Codememory\ApiBundle\DependencyInjection;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\Multithreading\ProcessManager;
use Codememory\ApiBundle\Multithreading\ProcessOptions;
use Codememory\ApiBundle\Multithreading\WorkerOptions;
use Codememory\ApiBundle\Services\PaginationQueryProcessor;
use Codememory\ApiBundle\Services\Paginator\Interfaces\PaginatorOptionsInterface;
use Codememory\ApiBundle\Services\Paginator\Paginator;
use Codememory\ApiBundle\Services\Paginator\PaginatorOptions;
use Codememory\ApiBundle\Services\QueryProcessor\FilterQueryProcessor;
use Codememory\ApiBundle\Services\QueryProcessor\SortQueryProcessor;
use Codememory\ApiBundle\Validator\Assert\AssertValidator;
use Codememory\ApiBundle\Validator\JsonSchema\JsonSchemaValidator;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ApiExtension extends Extension
{
    public function getAlias(): string
    {
        return 'codememory_api';
    }

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->registerWorkerOptions($config['threading']['worker_options'], $container);
        $this->registerProcessOptions($config['threading']['process_options'], $container);
        $this->registerProcessManager($container);
        $this->registerJsonSchemaValidator($container);
        $this->registerAssertValidator($config['assert'], $container);
        $this->registerPaginationParameters($config['pagination'], $container);
        $this->registerQueryProcessors($container);
        $this->registerPaginator($config['pagination'], $container);
    }

    private function registerWorkerOptions(array $options, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::WORKER_OPTIONS_SERVICE_ID, WorkerOptions::class)
            ->setArguments([
                '$delayBetweenParentIteration' => $options['delay_between_parent_process_iteration']
            ]);

        $container->setAlias(WorkerOptions::class, ApiBundle::WORKER_OPTIONS_SERVICE_ID);
    }

    private function registerProcessOptions(array $options, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::PROCESS_OPTIONS_SERVICE_ID, ProcessOptions::class)
            ->setArguments([
                '$delayBetweenIterationCallbackLogicCall' => $options['delay_between_iteration_callback_logic_call'],
                '$maxMemoryUsage' => $options['max_memory_usage']
            ]);

        $container->setAlias(ProcessOptions::class, ApiBundle::PROCESS_OPTIONS_SERVICE_ID);
    }

    private function registerProcessManager(ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::PROCESS_MANAGER_SERVICE_ID, ProcessManager::class)
            ->setArguments([
                '$workerOptions' => new Reference(WorkerOptions::class),
                '$processOptions' => new Reference(ProcessOptions::class)
            ]);

        $container->setAlias(ProcessManager::class, ApiBundle::PROCESS_MANAGER_SERVICE_ID);
    }

    private function registerJsonSchemaValidator(ContainerBuilder $container): void
    {
        $container->register(ApiBundle::JSON_SCHEMA_VALIDATOR_SERVICE_ID, JsonSchemaValidator::class);

        $container->setAlias(JsonSchemaValidator::class, ApiBundle::JSON_SCHEMA_VALIDATOR_SERVICE_ID);
    }

    private function registerAssertValidator(array $config, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::ASSERT_VALIDATOR_SERVICE_ID, AssertValidator::class)
            ->setArguments([
                '$validator' => new Reference(ValidatorInterface::class),
                '$config' => $config
            ]);

        $container->setAlias(AssertValidator::class, ApiBundle::ASSERT_VALIDATOR_SERVICE_ID);
    }

    private function registerPaginationParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter(ApiBundle::PAGINATION_MIN_LIMIT_PARAMETER, $config['min_limit']);
        $container->setParameter(ApiBundle::PAGINATION_MAX_LIMIT_PARAMETER, $config['max_limit']);
    }

    private function registerPaginator(array $config, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::PAGINATION_DEFAULT_OPTIONS_SERVICE_ID, PaginatorOptions::class)
            ->setArguments([
                '$paginationQueryProcessor' => new Reference(PaginationQueryProcessor::class),
                '$minLimit' => $container->getParameter(ApiBundle::PAGINATION_MIN_LIMIT_PARAMETER),
                '$maxLimit' => $container->getParameter(ApiBundle::PAGINATION_MAX_LIMIT_PARAMETER),
            ]);

        $container->setAlias(PaginatorOptionsInterface::class, $config['options_service']);

        $container
            ->register(Paginator::class, Paginator::class)
            ->setArguments([
                '$options' => new Reference($config['options_service'])
            ]);
    }

    private function registerQueryProcessors(ContainerBuilder $container): void
    {
        $container
            ->register(FilterQueryProcessor::class, FilterQueryProcessor::class)
            ->setArguments([
                '$requestStack' => new Reference(RequestStack::class),
                '$jsonSchemaValidator' => new Reference(JsonSchemaValidator::class)
            ]);

        $container
            ->register(SortQueryProcessor::class, SortQueryProcessor::class)
            ->setArguments([
                '$requestStack' => new Reference(RequestStack::class),
                '$jsonSchemaValidator' => new Reference(JsonSchemaValidator::class)
            ]);

        $container
            ->register(PaginationQueryProcessor::class, PaginationQueryProcessor::class)
            ->setArguments([
                '$requestStack' => new Reference(RequestStack::class),
                '$jsonSchemaValidator' => new Reference(JsonSchemaValidator::class)
            ]);
    }
}