<?php

namespace Codememory\ApiBundle\EventListener\KernelControllerArguments;

use Codememory\ApiBundle\Event\ControllerArgumentEvent;
use function is_array;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

final readonly class EventDispatchArgumentEventListener
{
    public function __construct(
        private ArgumentMetadataFactoryInterface $argumentMetadataFactory,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        foreach ($this->argumentMetadataFactory->createArgumentMetadata($event->getController()) as $argumentMetadata) {
            $this->dispatchArgumentEvent($event, $argumentMetadata);
        }
    }

    private function dispatchArgumentEvent(ControllerArgumentsEvent $event, ArgumentMetadata $argumentMetadata): void
    {
        $this->eventDispatcher->dispatch(new ControllerArgumentEvent(
            $event->getKernel(),
            $event->getRequest(),
            $event->getRequestType(),
            is_array($event->getController()) ? $event->getController()[0] : $event->getController(),
            $argumentMetadata,
            $event->getNamedArguments()[$argumentMetadata->getName()]
        ), ControllerArgumentEvent::NAME);
    }
}