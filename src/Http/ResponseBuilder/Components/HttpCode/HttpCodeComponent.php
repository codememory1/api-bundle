<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\HttpCode;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Exceptions\ResponseComponentDoesNotSupportAddingSubcomponentException;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;
use Override;

class HttpCodeComponent extends AbstractComponent implements ResponseComponentInterface
{
    public const string NAME = 'http_code';

    public function __construct(
        private readonly int $httpCode
    ) {
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    #[Override]
    public function addSubcomponent(ResponseSubcomponentInterface $subcomponent): static
    {
        throw new ResponseComponentDoesNotSupportAddingSubcomponentException(self::class);
    }

    public function getValue(): int
    {
        return $this->httpCode;
    }
}