<?php

namespace Codememory\ApiBundle\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class HttpException extends Exception
{
    public readonly int $httpCode;
    public readonly int $platformCode;
    public readonly array $messageParameters;
    public readonly array $headers;

    #[Pure]
    public function __construct(int $httpCode, int $platformCode, string $message, array $messageParameters = [], array $headers = [])
    {
        parent::__construct($message);

        $this->httpCode = $httpCode;
        $this->platformCode = $platformCode;
        $this->messageParameters = $messageParameters;
        $this->headers = $headers;
    }
}