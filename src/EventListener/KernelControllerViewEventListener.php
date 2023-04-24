<?php

namespace Codememory\ApiBundle\EventListener;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class KernelControllerViewEventListener
{
    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof ResponseSchemaInterface) {
            $event->setResponse(new JsonResponse(
                $controllerResult->toArray(),
                $controllerResult->getHttpCode(),
                $controllerResult->getHeaders()
            ));
        }
    }
}