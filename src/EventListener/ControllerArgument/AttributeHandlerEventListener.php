<?php

namespace Codememory\ApiBundle\EventListener\ControllerArgument;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentDecoratorRegistryInterface;
use Codememory\ApiBundle\Event\ControllerArgumentEvent;
use Codememory\ApiBundle\Exceptions\DecoratorHandlerNotRegisteredException;

final readonly class AttributeHandlerEventListener
{
    public function __construct(
        private ControllerArgumentDecoratorRegistryInterface $controllerArgumentDecoratorRegistry
    ) {
    }

    public function onControllerArgument(ControllerArgumentEvent $event): void
    {
        foreach ($event->argumentMetadata->getAttributes() as $attribute) {
            $this->attributeHandler($attribute, $event);
        }
    }

    private function attributeHandler(object $attribute, ControllerArgumentEvent $event): void
    {
        if ($attribute instanceof ControllerArgumentDecoratorInterface) {
            $handler = $this->controllerArgumentDecoratorRegistry->getHandler($attribute->getHandler());

            if (null === $handler) {
                throw new DecoratorHandlerNotRegisteredException($attribute::class, $attribute->getHandler(), ApiBundle::DECORATOR_TAG);
            }

            $handler->handle($attribute, $event->argumentMetadata, $event->controller, $event->argumentValue);
        }
    }
}