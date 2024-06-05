<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Meta;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;

class RateLimitSubcomponent extends AbstractComponent implements ResponseSubcomponentInterface
{
    public const string NAME = 'rate_limit';
    protected array $value;

    public function __construct(
        private readonly int $limit
    ) {
        $this->value = [
            'limit' => $this->limit
        ];
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}