<?php

namespace Codememory\ApiBundle\ResponseSchema\View;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;

final class MessageView implements ViewInterface
{
    public function __construct(
        private readonly string $message,
        private readonly bool $isError = false,
        private readonly array $messageParameters = []
    ) {
    }

    public function isError(): bool
    {
        return $this->isError;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'message_parameters' => $this->messageParameters
        ];
    }
}