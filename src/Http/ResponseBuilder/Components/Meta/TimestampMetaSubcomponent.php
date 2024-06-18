<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Meta;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Exceptions\ResponseComponentDoesNotSupportAddingSubcomponentException;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;
use Override;

class TimestampMetaSubcomponent extends AbstractComponent implements ResponseSubcomponentInterface
{
    public const string NAME = 'timestamp';

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
        return time();
    }
}