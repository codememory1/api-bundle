<?php

namespace Codememory\ApiBundle\HttpErrorHandler\Interfaces;

interface HttpErrorHandlerConfigurationInterface
{
    public function getAccessIsDeniedMessage(): string;

    public function getAccessIsDeniedPlatformCode(): int;

    public function getNotFoundMessage(): string;

    public function getNotFoundPlatformCode(): int;

    public function getMethodNotAllowedMessage(): string;

    public function getMethodNotAllowedPlatformCode(): int;

    public function getServerErrorMessage(): string;

    public function getServerErrorPlatformCode(): int;
}