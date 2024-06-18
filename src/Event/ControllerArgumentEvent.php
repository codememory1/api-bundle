<?php

namespace Codememory\ApiBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final readonly class ControllerArgumentEvent
{
    public const string NAME = 'codememory.controller_argument';

    public function __construct(
        public HttpKernelInterface $kernel,
        public Request $request,
        public int $requestType,
        public object $controller,
        public ArgumentMetadata $argumentMetadata,
        public mixed $argumentValue
    ) {
    }
}