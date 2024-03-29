<?php

namespace Codememory\ApiBundle\ResponseSchema\View;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;

final readonly class SuccessView implements ViewInterface
{
    public function __construct(
        private array $data
    ) {
    }

    public function isError(): bool
    {
        return false;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data
        ];
    }
}