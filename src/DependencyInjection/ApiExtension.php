<?php

namespace Codememory\ApiBundle\DependencyInjection;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorRegistryInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorRegistryInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerClassMethodDecoratorRegistryInterface;
use Codememory\ApiBundle\AttributeHandler\Registry\ControllerArgumentDecoratorRegistry;
use Codememory\ApiBundle\AttributeHandler\Registry\ControllerArgumentValueResolverDecoratorRegistry;
use Codememory\ApiBundle\AttributeHandler\Registry\ControllerClassMethodDecoratorRegistry;
use Codememory\ApiBundle\Http\Exception\HttpExceptionConfiguration;
use Codememory\ApiBundle\Http\Exception\Interfaces\HttpExceptionConfigurationInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\ResponseBuilder;
use Codememory\ApiBundle\JWT\Interfaces\JWTInterface;
use Codememory\ApiBundle\JWT\JWT;
use Codememory\ApiBundle\Multithreading\ProcessManager;
use Codememory\ApiBundle\Multithreading\ProcessOptions;
use Codememory\ApiBundle\Multithreading\WorkerOptions;
use Codememory\ApiBundle\Paginator\ArrayPaginator;
use Codememory\ApiBundle\Paginator\DoctrinePaginator;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorConfigurationInterface;
use Codememory\ApiBundle\Paginator\Interfaces\PaginatorOptionsInterface;
use Codememory\ApiBundle\Paginator\PaginatorConfiguration;
use Codememory\ApiBundle\Paginator\PaginatorOptions;
use Codememory\ApiBundle\QueryProcessor\FilterQueryProcessor;
use Codememory\ApiBundle\QueryProcessor\PaginationQueryProcessor;
use Codememory\ApiBundle\QueryProcessor\SortQueryProcessor;
use Codememory\ApiBundle\Resolver\ControllerArgumentValueAttributeResolver;
use Codememory\ApiBundle\Validator\Assert\AssertErrorHandler;
use Codememory\ApiBundle\Validator\Assert\AssertValidator;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertErrorHandlerInterface;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertValidatorInterface;
use Codememory\ApiBundle\Validator\JsonSchema\JsonSchemaValidator;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

        $loader->load('api.yaml');
        $loader->load('api_decorators.yaml');
        $loader->load('api_event_listeners.yaml');
        $loader->load('api_commands.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->registerExceptionHandler($container, $config['http']['exception']);
        $this->registerAssertServices($container, $config['assert']);
        $this->registerWorkerOptions($config['threading']['worker_options'], $container);
        $this->registerProcessOptions($config['threading']['process_options'], $container);
        $this->registerPaginator($config['pagination'], $container);
        $this->registerJWT($container);
        $this->registerProcessManager($container);
        $this->registerJsonSchemaValidator($container);
        $this->registerQueryProcessors($container);
        $this->registerResolver($container);

        $this->registerResponseBuilder($container);
        $this->registerControllerArgumentValueRegistryResolver($container, $config['decorators']['controller_argument_value']);
        $this->registerControllerClassMethodRegistryResolver($container, $config['decorators']['controller_class_method']);
        $this->registerControllerArgumentResolver($container, $config['decorators']['controller_argument']);
    }

    private function registerExceptionHandler(ContainerBuilder $container, array $config): void
    {
        $container
            ->register(ApiBundle::HTTP_EXCEPTION_DEFAULT_CONFIGURATION_SERVICE_ID, HttpExceptionConfiguration::class)
            ->addMethodCall('setConfig', [$config]);

        $container->setAlias(HttpExceptionConfigurationInterface::class, $config['config_service']);
    }

    private function registerAssertServices(ContainerBuilder $container, array $config): void
    {
        $container
            ->register(ApiBundle::ASSERT_DEFAULT_VALIDATOR_SERVICE, AssertValidator::class)
            ->setArgument('$validator', new Reference(ValidatorInterface::class))
            ->setArgument('$mainErrorHandler', new Reference(AssertErrorHandlerInterface::class))
            ->setArgument('$eventDispatcher', new Reference(EventDispatcherInterface::class));

        $container->register(ApiBundle::ASSERT_DEFAULT_ERROR_HANDLER_SERVICE, AssertErrorHandler::class);

        $container->setAlias(AssertValidatorInterface::class, $config['validator']);
        $container->setAlias(AssertErrorHandlerInterface::class, $config['error_handler']);
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

        $container
            ->register(DoctrinePaginator::class, DoctrinePaginator::class)
            ->setArgument('$options', new Reference(PaginatorOptionsInterface::class));

        $container
            ->register(ArrayPaginator::class, ArrayPaginator::class)
            ->setArgument('$options', new Reference(PaginatorOptionsInterface::class));

        $container->setAlias(PaginatorConfigurationInterface::class, $config['configuration_service']);
        $container->setAlias(PaginatorOptionsInterface::class, $config['options_service']);
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
            ->register(ControllerArgumentValueAttributeResolver::class, ControllerArgumentValueAttributeResolver::class)
            ->setArguments([
                '$controllerArgumentValueDecoratorRegistry' => new Reference(ControllerArgumentValueResolverDecoratorRegistryInterface::class)
            ])
            ->addTag('controller.argument_value_resolver');
    }

    private function registerResponseBuilder(ContainerBuilder $container): void
    {
        $container
            ->register(ResponseBuilderInterface::class, ResponseBuilder::class)
            ->addArgument(new Reference(EventDispatcherInterface::class));
    }

    private function registerJWT(ContainerBuilder $container): void
    {
        $container->register(JWTInterface::class, JWT::class);
    }

    private function registerControllerArgumentValueRegistryResolver(ContainerBuilder $container, array $config): void
    {
        $container->register(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID, ControllerArgumentValueResolverDecoratorRegistry::class);
        $container->setParameter(ApiBundle::CONTROLLER_ARGUMENT_VALUE_RESOLVER_DECORATOR_REGISTRY_SERVICE_PARAMETER, $config['registry_service']);

        $container->setAlias(ControllerArgumentValueResolverDecoratorRegistryInterface::class, $config['registry_service']);
    }

    private function registerControllerClassMethodRegistryResolver(ContainerBuilder $container, array $config): void
    {
        $container->register(ApiBundle::CONTROLLER_CLASS_METHOD_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID, ControllerClassMethodDecoratorRegistry::class);

        $container->setParameter(ApiBundle::CONTROLLER_CLASS_METHOD_DECORATOR_REGISTRY_SERVICE_PARAMETER, $config['registry_service']);

        $container->setAlias(ControllerClassMethodDecoratorRegistryInterface::class, $config['registry_service']);
    }

    private function registerControllerArgumentResolver(ContainerBuilder $container, array $config): void
    {
        $container->register(ApiBundle::CONTROLLER_ARGUMENT_DEFAULT_DECORATOR_REGISTRY_SERVICE_ID, ControllerArgumentDecoratorRegistry::class);

        $container->setParameter(ApiBundle::CONTROLLER_ARGUMENT_DECORATOR_REGISTRY_SERVICE_PARAMETER, $config['registry_service']);

        $container->setAlias(ControllerArgumentDecoratorRegistryInterface::class, $config['registry_service']);
    }
}