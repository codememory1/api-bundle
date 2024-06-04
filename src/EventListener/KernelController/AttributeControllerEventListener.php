<?php

namespace Codememory\ApiBundle\EventListener\KernelController;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerClassMethodDecoratorInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerClassMethodDecoratorRegistryInterface;
use Codememory\ApiBundle\Exceptions\DecoratorHandlerNotRegisteredException;
use function is_array;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

final readonly class AttributeControllerEventListener
{
    public function __construct(
        private ControllerClassMethodDecoratorRegistryInterface $controllerClassMethodDecoratorRegistry
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (is_array($event->getController())) {
            foreach ($event->getAttributes() as $attributes) {
                foreach ($attributes as $attribute) {
                    $this->attributeHandler($attribute, $event);
                }
            }
        }
    }

    private function attributeHandler(object $attribute, ControllerEvent $event): void
    {
        if ($attribute instanceof ControllerClassMethodDecoratorInterface) {
            $handler = $this->controllerClassMethodDecoratorRegistry->getHandler($attribute->getHandler());

            if (null === $handler) {
                throw new DecoratorHandlerNotRegisteredException($attribute::class, $attribute->getHandler(), ApiBundle::CONTROLLER_CLASS_METHOD_DECORATOR_TAG);
            }

            $handler->handler($attribute, $event->getController()[0]);
        }
    }
}