<?php

namespace Codememory\ApiBundle\EventListener\KernelController;

use Codememory\ApiBundle\Services\Decorator\Decorator;
use function is_array;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

final readonly class AttributeControllerEventListener
{
    public function __construct(
        private Decorator $decorator
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (is_array($event->getController())) {
            $controller = $event->getController()[0];
            $method = $event->getController()[1];

            $reflectionClass = new ReflectionClass($controller);

            $this->decorator->handle($reflectionClass->getAttributes(), $controller);
            $this->decorator->handle($reflectionClass->getMethod($method)->getAttributes(), $controller);
        }
    }
}