<?php

namespace Codememory\ApiBundle\EventListener\KernelException;

use Codememory\ApiBundle\Http\Exception\Interfaces\HttpExceptionConfigurationInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Components\Error\ErrorComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Components\Status\StatusComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as SymfonyHttpExceptionInterface;

final readonly class HttpExceptionEventListener
{
    public function __construct(
        private ResponseBuilderInterface $responseBuilder,
        private HttpExceptionConfigurationInterface $configuration,
        private string $env,
        private bool $debug
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($this->canBeProcessed() && !in_array($exception::class, $this->configuration->getExcludedExceptions(), true)) {
            if ($exception instanceof SymfonyHttpExceptionInterface) {
                $this->handler($event, $exception->getStatusCode(), $exception->getMessage(), $exception->getHeaders());
            }
        }
    }

    private function canBeProcessed(): bool
    {
        return PHP_SAPI !== 'cli' || $this->isDev() || $this->debug;
    }

    private function isDev(): bool
    {
        return str_starts_with($this->env, 'dev');
    }

    private function handler(ExceptionEvent $event, int $statusCode, string $message, array $headers = []): void
    {
        $this->buildResponse($statusCode, $message);

        $event->setResponse(new JsonResponse($this->responseBuilder->build(), $statusCode, $headers));
    }

    private function buildResponse(int $statusCode, string $message): void
    {
        $this->responseBuilder->addComponent(new StatusComponent('error'));
        $this->responseBuilder->addComponent(new ErrorComponent(Response::$statusTexts[$statusCode], $message));
    }
}