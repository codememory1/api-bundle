<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Meta;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Exceptions\ResponseComponentDoesNotSupportAddingSubcomponentException;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;
use Override;

class ApiVersionMetaSubcomponent extends AbstractComponent implements ResponseSubcomponentInterface
{
    public const string NAME = 'api_version';

    public function __construct(
        private readonly float $version
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

    public function getValue(): float
    {
        return "v{$this->version}";
    }
}