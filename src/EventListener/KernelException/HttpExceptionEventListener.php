<?php

namespace Codememory\ApiBundle\EventListener\KernelException;

use Codememory\ApiBundle\Exceptions\HttpException;
use Codememory\ApiBundle\ResponseSchema\ResponseSchema;
use Codememory\ApiBundle\ResponseSchema\View\MessageView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class HttpExceptionEventListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException && PHP_SAPI !== 'cli') {
            $responseSchema = new ResponseSchema();

            $responseSchema->setHttpCode($exception->httpCode);
            $responseSchema->setPlatformCode($exception->platformCode);
            $responseSchema->setView(new MessageView($exception->getMessage(), true, $exception->messageParameters));

            (new JsonResponse(
                $responseSchema->toArray(),
                $responseSchema->getHttpCode(),
                $responseSchema->getHeaders()
            )
            )->send();
        }
    }
}