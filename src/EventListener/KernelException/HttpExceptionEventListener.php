<?php

namespace Codememory\ApiBundle\EventListener\KernelException;

use Codememory\ApiBundle\Exceptions\HttpException;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\ResponseSchema;
use Codememory\ApiBundle\ResponseSchema\View\MessageView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class HttpExceptionEventListener
{
    public function __construct(
        private readonly string $env,
        private readonly array $httpErrorHandlerConfig
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (PHP_SAPI !== 'cli') {
            $responseSchema = new ResponseSchema();

            if ($exception instanceof HttpException) {
                $responseSchema->setHttpCode($exception->httpCode);
                $responseSchema->setPlatformCode($exception->platformCode);
                $responseSchema->setView(new MessageView($exception->getMessage(), true, $exception->messageParameters));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof NotFoundHttpException) {
                $responseSchema->setHttpCode(404);
                $responseSchema->setPlatformCode($this->httpErrorHandlerConfig[404]['platform_code']);
                $responseSchema->setView(new MessageView($this->httpErrorHandlerConfig[404]['message'], true));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                $responseSchema->setHttpCode(405);
                $responseSchema->setPlatformCode($this->httpErrorHandlerConfig[405]['platform_code']);
                $responseSchema->setView(new MessageView($this->httpErrorHandlerConfig[405]['message'], true));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof AccessDeniedHttpException) {
                $responseSchema->setHttpCode(403);
                $responseSchema->setPlatformCode($this->httpErrorHandlerConfig[403]['platform_code']);
                $responseSchema->setView(new MessageView($this->httpErrorHandlerConfig[403]['message'], true));

                $this->jsonResponse($responseSchema);
            } else {
                if ($this->env !== 'dev') {
                    $responseSchema->setHttpCode(500);
                    $responseSchema->setPlatformCode($this->httpErrorHandlerConfig[500]['platform_code']);
                    $responseSchema->setView(new MessageView($this->httpErrorHandlerConfig[500]['message'], true));

                    $this->jsonResponse($responseSchema);
                }
            }
        }
    }

    private function jsonResponse(ResponseSchemaInterface $responseSchema): void
    {
        (new JsonResponse(
            $responseSchema->toArray(),
            $responseSchema->getHttpCode(),
            $responseSchema->getHeaders()
        )
        )->send();
    }
}