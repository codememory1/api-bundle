<?php

namespace Codememory\ApiBundle\ResponseSchema\View;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;

final readonly class MessageView implements ViewInterface
{
    public function __construct(
        private string $message,
        private bool $isError = false,
        private array $messageParameters = []
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