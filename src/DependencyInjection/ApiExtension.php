<?php

namespace Codememory\ApiBundle\DependencyInjection;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\EventListener\KernelException\HttpExceptionEventListener;
use Codememory\ApiBundle\Factory\ERCConfigurationFactory;
use Codememory\ApiBundle\Factory\ResponseSchemaFactory;
use Codememory\ApiBundle\HttpErrorHandler\HttpErrorHandlerConfiguration;
use Codememory\ApiBundle\HttpErrorHandler\Interfaces\HttpErrorHandlerConfigurationInterface;
use Codememory\ApiBundle\Multithreading\ProcessManager;
use Codememory\ApiBundle\Multithreading\ProcessOptions;
use Codememory\ApiBundle\Multithreading\WorkerOptions;
use Codememory\ApiBundle\Paginator\DoctrinePaginator;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorConfigurationInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorOptionsInterface;
use Codememory\ApiBundle\Paginator\PaginatorConfiguration;
use Codememory\ApiBundle\Paginator\PaginatorOptions;
use Codememory\ApiBundle\Resolver\ControllerEntityArgumentResolver;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaFactoryInterface;
use Codememory\ApiBundle\Services\QueryProcessor\FilterQueryProcessor;
use Codememory\ApiBundle\Services\QueryProcessor\PaginationQueryProcessor;
use Codememory\ApiBundle\Services\QueryProcessor\SortQueryProcessor;
use Codememory\ApiBundle\Validator\Assert\AssertValidator;
use Codememory\ApiBundle\Validator\JsonSchema\JsonSchemaValidator;
use Codememory\Dto\DataKeyNamingStrategy\DataKeyNamingStrategySnakeCase;
use Codememory\Dto\DecoratorHandlerRegistrar;
use Codememory\Dto\Provider\DataTransferObjectPublicPropertyProvider;
use Codememory\Reflection\ReflectorManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Codememory\Dto\Collectors\BaseCollector as DTOBaseCollector;
use Codememory\ApiBundle\Factory\DTOConfigurationFactory;
use Codememory\Dto\Factory\ExecutionContextFactory as DTOExecutionContextFactory;
use Codememory\EntityResponseControl\Collectors\BaseCollector as ERCCollector;
use Codememory\EntityResponseControl\Factory\ExecutionContextFactory as ERCContextFactory;
use Codememory\EntityResponseControl\ResponseKeyNamingStrategy\ResponseKeyNamingStrategySnakeCase;
use Codememory\EntityResponseControl\Provider\ResponsePrototypePrivatePropertyProvider;
use Codememory\EntityResponseControl\DecoratorHandlerRegistrar as ERCDecoratorHandlerRegistrar;

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

        $this->registerDTOParameters($config['dto'], $container);
        $this->registerDefaultDTOServices($config['dto'], $container);

        $this->registerERCParameters($config['erc'], $container);
        $this->registerDefaultERCServices($container);

        $this->registerResponseSchema($config['response_schema'], $container);

        $this->registerHttpErrorHandler($config['http_error_handler'], $container);

        $this->registerWorkerOptions($config['threading']['worker_options'], $container);
        $this->registerProcessOptions($config['threading']['process_options'], $container);

        $this->registerProcessManager($container);
        $this->registerJsonSchemaValidator($container);
        $this->registerAssertValidator($config['assert'], $container);
        $this->registerQueryProcessors($container);
        $this->registerPaginator($config['pagination'], $container);
        $this->registerResolver($container);
    }

    private function registerDefaultDTOServices(array $config, ContainerBuilder $container): void
    {
        $container->register(ApiBundle::DTO_DEFAULT_COLLECTOR_SERVICE, DTOBaseCollector::class);
        $container->register(ApiBundle::DTO_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE, DTOExecutionContextFactory::class);
        $container->register(ApiBundle::DTO_DEFAULT_DATA_KEY_NAMING_STRATEGY_SERVICE, DataKeyNamingStrategySnakeCase::class);
        $container->register(ApiBundle::DTO_DEFAULT_PROPERTY_PROVIDER_SERVICE, DataTransferObjectPublicPropertyProvider::class);
        $container->register(ApiBundle::DTO_REFLECTOR_MANAGER_SERVICE, ReflectorManager::class);
        $container->register(ApiBundle::DTO_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE, DecoratorHandlerRegistrar::class);

        $container
            ->register(ApiBundle::DTO_DEFAULT_CACHE_ADAPTER_SERVICE, FilesystemAdapter::class)
            ->setArguments([
                '$namespace' => 'dto',
                '$directory' => "{$container->getParameter('kernel.cache_dir')}/codememory"
            ]);

        $container
            ->register(ApiBundle::DTO_DEFAULT_CONFIGURATION_FACTORY_SERVICE, DTOConfigurationFactory::class)
            ->setArguments([
                '$dataKeyNamingStrategy' => new Reference($config['data_key_strategy']['service']),
                '$dataTransferObjectPropertyProvider' => new Reference($config['dto_property_provider']['service'])
            ]);
    }

    private function registerDTOParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter(ApiBundle::DTO_COLLECTOR_PARAMETER, $config['collector']['service']);
        $container->setParameter(ApiBundle::DTO_CONFIGURATION_FACTORY_PARAMETER, $config['configuration']['factory_service']);
        $container->setParameter(ApiBundle::DTO_EXECUTION_CONTEXT_FACTORY_PARAMETER, $config['context']['factory_service']);
        $container->setParameter(ApiBundle::DTO_CACHE_PARAMETER, $config['cache']['adapter']);
        $container->setParameter(ApiBundle::DTO_DATA_KEY_NAMING_STRATEGY_PARAMETER, $config['data_key_strategy']['service']);
        $container->setParameter(ApiBundle::DTO_PROPERTY_PROVIDER_PARAMETER, $config['dto_property_provider']['service']);
        $container->setParameter(ApiBundle::DTO_DECORATOR_HANDLER_REGISTRAR_PARAMETER, $config['decorator_handler_registrar']['service']);
    }

    private function registerDefaultERCServices(ContainerBuilder $container): void
    {
        $container->register(ApiBundle::ERC_DEFAULT_COLLECTOR_SERVICE, ERCCollector::class);
        $container->register(ApiBundle::ERC_DEFAULT_EXECUTION_CONTEXT_FACTORY_SERVICE, ERCContextFactory::class);
        $container->register(ApiBundle::ERC_DEFAULT_RESPONSE_KEY_NAMING_STRATEGY_SERVICE, ResponseKeyNamingStrategySnakeCase::class);
        $container->register(ApiBundle::ERC_DEFAULT_PROPERTY_PROVIDER_SERVICE, ResponsePrototypePrivatePropertyProvider::class);
        $container->register(ApiBundle::ERC_REFLECTOR_MANAGER_SERVICE, ReflectorManager::class);
        $container->register(ApiBundle::ERC_DEFAULT_DECORATOR_HANDLER_REGISTRAR_SERVICE, ERCDecoratorHandlerRegistrar::class);

        $container
            ->register(ApiBundle::ERC_DEFAULT_CACHE_ADAPTER_SERVICE, FilesystemAdapter::class)
            ->setArguments([
                '$namespace' => 'erc',
                '$directory' => "{$container->getParameter('kernel.cache_dir')}/codememory"
            ]);

        $container
            ->register(ApiBundle::ERC_DEFAULT_CONFIGURATION_FACTORY_SERVICE, ERCConfigurationFactory::class)
            ->setArguments([
                '$responseKeyNamingStrategySnakeCase' => new Reference(ApiBundle::ERC_DEFAULT_RESPONSE_KEY_NAMING_STRATEGY_SERVICE),
                '$responsePrototypePropertyProvider' => new Reference(ApiBundle::ERC_DEFAULT_PROPERTY_PROVIDER_SERVICE)
            ]);
    }

    private function registerERCParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter(ApiBundle::ERC_COLLECTOR_PARAMETER, $config['collector']['service']);
        $container->setParameter(ApiBundle::ERC_CONFIGURATION_FACTORY_PARAMETER, $config['configuration']['factory_service']);
        $container->setParameter(ApiBundle::ERC_EXECUTION_CONTEXT_FACTORY_PARAMETER, $config['context']['factory_service']);
        $container->setParameter(ApiBundle::ERC_CACHE_PARAMETER, $config['cache']['adapter']);
        $container->setParameter(ApiBundle::ERC_RESPONSE_KEY_NAMING_STRATEGY_PARAMETER, $config['response_key_strategy']['service']);
        $container->setParameter(ApiBundle::ERC_PROPERTY_PROVIDER_PARAMETER, $config['prototype_property_provider']['service']);
        $container->setParameter(ApiBundle::ERC_DECORATOR_HANDLER_REGISTRAR_PARAMETER, $config['decorator_handler_registrar']['service']);
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

    private function registerPaginator(array $config, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::PAGINATION_DEFAULT_CONFIGURATION_SERVICE, PaginatorConfiguration::class)
            ->setArgument('$config', $config);

        $container
            ->register(ApiBundle::PAGINATION_DEFAULT_OPTIONS_SERVICE, PaginatorOptions::class)
            ->setArguments([
                '$paginationQueryProcessor' => new Reference(PaginationQueryProcessor::class),
                '$configuration' => new Reference($config['configuration_service'])
            ]);

        $container
            ->register(ApiBundle::PAGINATION_DEFAULT_PAGINATOR, DoctrinePaginator::class)
            ->setArgument('$options', new Reference($config['options_service']));

        $container->setAlias(PaginatorConfigurationInterface::class, $config['configuration_service']);
        $container->setAlias(PaginatorInterface::class, $config['paginator_service']);
        $container->setAlias(PaginatorOptionsInterface::class, $config['options_service']);
    }

    private function registerHttpErrorHandler(array $config, ContainerBuilder $container): void
    {
        $container
            ->register(ApiBundle::HTTP_ERROR_HANDLER_DEFAULT_CONFIGURATION, HttpErrorHandlerConfiguration::class)
            ->setArgument('$config', $config);

        $container
            ->register(HttpExceptionEventListener::class, HttpExceptionEventListener::class)
            ->setArguments([
                '$env' => $container->getParameter('kernel.environment'),
                '$configuration' => new Reference($config['configuration_service']),
                '$responseSchemaFactory' => new Reference(ResponseSchemaFactoryInterface::class)
            ])
            ->addTag('kernel.event_listener', [
                'event' => 'kernel.exception',
                'method' => 'onKernelException'
            ]);

        $container->setAlias(HttpErrorHandlerConfigurationInterface::class, $config['configuration_service']);
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

    private function registerResolver(ContainerBuilder $container): void
    {
        $container
            ->register(ControllerEntityArgumentResolver::class, ControllerEntityArgumentResolver::class)
            ->setArguments([
                '$em' => new Reference(EntityManagerInterface::class),
                '$container' => new Reference(ContainerInterface::class),
                '$decorator' => new Reference(ApiBundle::DECORATOR_SERVICE_ID)
            ])
            ->addTag('controller.argument_value_resolver');
    }

    private function registerResponseSchema(array $config, ContainerBuilder $container): void
    {
        $container->register(ApiBundle::RESPONSE_SCHEMA_DEFAULT_FACTORY, ResponseSchemaFactory::class);

        $container->setAlias(ResponseSchemaFactoryInterface::class, $config['factory_service']);
    }
}