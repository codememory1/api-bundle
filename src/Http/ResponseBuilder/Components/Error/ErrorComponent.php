<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Error;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;

class ErrorComponent extends AbstractComponent
{
    protected array $value = [];

    public function __construct(
        private readonly string $message,
        private readonly string $details
    ) {
        $this->value['message'] = $this->message;
        $this->value['details'] = $this->details;
    }

    public function getKey(): string
    {
        return 'error';
    }

    public function getValue(): array
    {
        return $this->value;
    }
}