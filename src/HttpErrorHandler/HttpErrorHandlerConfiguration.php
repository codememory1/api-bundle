<?php

namespace Codememory\ApiBundle\HttpErrorHandler;

use Codememory\ApiBundle\HttpErrorHandler\Interfaces\HttpErrorHandlerConfigurationInterface;

final readonly class HttpErrorHandlerConfiguration implements HttpErrorHandlerConfigurationInterface
{
    public function __construct(
        private array $config
    ) {
    }

    public function getAccessIsDeniedMessage(): string
    {
        return $this->config[403]['message'];
    }

    public function getAccessIsDeniedPlatformCode(): int
    {
        return $this->config[403]['platform_code'];
    }

    public function getNotFoundMessage(): string
    {
        return $this->config[404]['message'];
    }

    public function getNotFoundPlatformCode(): int
    {
        return $this->config[404]['platform_code'];
    }

    public function getMethodNotAllowedMessage(): string
    {
        return $this->config[405]['message'];
    }

    public function getMethodNotAllowedPlatformCode(): int
    {
        return $this->config[405]['platform_code'];
    }

    public function getServerErrorMessage(): string
    {
        return $this->config[500]['message'];
    }

    public function getServerErrorPlatformCode(): int
    {
        return $this->config[500]['platform_code'];
    }
}