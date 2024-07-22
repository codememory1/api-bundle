<?php

namespace Codememory\ApiBundle\EventListener\KernelView;

use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ResponseFormattingEventListener
{
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if ($result instanceof ResponseBuilderInterface) {
            $event->setResponse(new JsonResponse(
                $result->getStatus(),
                $result->build(),
                $result->getHeaders()
            ));
        }
    }
}