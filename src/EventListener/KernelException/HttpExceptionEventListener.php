<?php

namespace Codememory\ApiBundle\EventListener\KernelException;

use Codememory\ApiBundle\Exceptions\HttpException;
use Codememory\ApiBundle\HttpErrorHandler\Interfaces\HttpErrorHandlerConfigurationInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaFactoryInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\View\MessageView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class HttpExceptionEventListener
{
    public function __construct(
        private HttpErrorHandlerConfigurationInterface $configuration,
        private ResponseSchemaFactoryInterface $responseSchemaFactory,
        private string $env,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (PHP_SAPI !== 'cli') {
            $responseSchema = $this->responseSchemaFactory->createResponseSchema();

            if ($exception instanceof HttpException) {
                $responseSchema->setHttpCode($exception->httpCode);
                $responseSchema->setPlatformCode($exception->platformCode);
                $responseSchema->setView(new MessageView($exception->getMessage(), true, $exception->messageParameters));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof NotFoundHttpException) {
                $responseSchema->setHttpCode(404);
                $responseSchema->setPlatformCode($this->configuration->getNotFoundPlatformCode());
                $responseSchema->setView(new MessageView($this->configuration->getNotFoundMessage(), true));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                $responseSchema->setHttpCode(405);
                $responseSchema->setPlatformCode($this->configuration->getMethodNotAllowedPlatformCode());
                $responseSchema->setView(new MessageView($this->configuration->getMethodNotAllowedMessage(), true));

                $this->jsonResponse($responseSchema);
            } else if ($exception instanceof AccessDeniedHttpException) {
                $responseSchema->setHttpCode(403);
                $responseSchema->setPlatformCode($this->configuration->getAccessIsDeniedPlatformCode());
                $responseSchema->setView(new MessageView($this->configuration->getAccessIsDeniedMessage(), true));

                $this->jsonResponse($responseSchema);
            } else {
                if ('dev' !== $this->env) {
                    $responseSchema->setHttpCode(500);
                    $responseSchema->setPlatformCode($this->configuration->getServerErrorPlatformCode());
                    $responseSchema->setView(new MessageView($this->configuration->getServerErrorMessage(), true));

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