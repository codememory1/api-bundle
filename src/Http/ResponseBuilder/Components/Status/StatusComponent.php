<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Status;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Exceptions\ResponseComponentDoesNotSupportAddingSubcomponentException;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;
use Override;

class StatusComponent extends AbstractComponent implements ResponseComponentInterface
{
    public const string NAME = 'status';

    public function __construct(
        private readonly string $status
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

    public function getValue(): string
    {
        return $this->status;
    }
}