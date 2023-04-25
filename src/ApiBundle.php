<?php

namespace Codememory\ApiBundle;

use Codememory\ApiBundle\DependencyInjection\ApiExtension;
use Codememory\ApiBundle\DependencyInjection\Compiler\AddDtoConstraintPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\AddResponseControlConstraintPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\AddResponseControlConstraintTypePass;
use Codememory\ApiBundle\DependencyInjection\Compiler\DtoObjectRegisterPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\RegisterDecoratorPass;
use Codememory\ApiBundle\DependencyInjection\Compiler\ResponseControlObjectRegisterPass;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ApiBundle extends Bundle
{
    public const DTO_OBJECT_TAG = 'codememory.dto.object';
    public const DTO_CONSTRAINT_TAG = 'codememory.dto.constraint';
    public const DTO_CACHE_TAG = 'codememory.dto.cache';
    public const DTO_COLLECTOR_TAG = 'codememory.dto.collector';
    public const DTO_DEFAULT_COLLECTOR_SERVICE_ID = 'codememory.dto.default_collector';
    public const DTO_DEFAULT_CACHE_SERVICE_ID = 'codememory.dto.default_cache';
    public const DTO_REFLECTOR_MANAGER_SERVICE_ID = 'codememory.dto.reflector_manager';
    public const DTO_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID = 'codememory.dto.constraint_handler_register';
    public const RESPONSE_CONTROL_OBJECT_TAG = 'codememory.response_control.object';
    public const RESPONSE_CONTROL_CONSTRAINT_TAG = 'codememory.response_control.constraint';
    public const RESPONSE_CONTROL_CONSTRAINT_TYPE_TAG = 'codememory.response_control.constraint_type';
    public const RESPONSE_CONTROL_CACHE_TAG = 'codememory.response_control.cache';
    public const RESPONSE_CONTROL_DISASSEMBLER_TAG = 'codememory.response_control.disassembler';
    public const RESPONSE_CONTROL_REFLECTOR_MANAGER_SERVICE_ID = 'codememory.response_control.reflector_manager';
    public const RESPONSE_CONTROL_DEFAULT_CACHE_SERVICE_ID = 'codememory.response_control.default_cache';
    public const RESPONSE_CONTROL_DEFAULT_DISASSEMBLER_SERVICE_ID = 'codememory.response_control.default_disassembler';
    public const RESPONSE_CONTROL_CONSTRAINT_HANDLER_REGISTER_SERVICE_ID = 'codememory.response_control.constraint_handler_register';
    public const RESPONSE_CONTROL_CONSTRAINT_TYPE_HANDLER_REGISTER_SERVICE_ID = 'codememory.response_control.constraint_type_handler_register';
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

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddDtoConstraintPass());
        $container->addCompilerPass(new DtoObjectRegisterPass());
        $container->addCompilerPass(new AddResponseControlConstraintTypePass());
        $container->addCompilerPass(new AddResponseControlConstraintPass());
        $container->addCompilerPass(new ResponseControlObjectRegisterPass());
        $container->addCompilerPass(new RegisterDecoratorPass());
    }

    #[Pure]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ApiExtension();
    }
}