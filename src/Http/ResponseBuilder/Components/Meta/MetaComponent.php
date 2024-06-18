<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Meta;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;

class MetaComponent extends AbstractComponent implements ResponseComponentInterface
{
    public const string NAME = 'meta';
    protected array $meta = [];

    public function __construct(array $subcomponents = [])
    {
        foreach ($subcomponents as $subcomponent) {
            $this->addSubcomponent($subcomponent);
        }
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    public function getValue(): array
    {
        return $this->meta;
    }
}