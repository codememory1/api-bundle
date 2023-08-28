<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class HttpPatch implements DecoratorInterface
{
    public function __construct(
        public array $assert = []
    ) {
    }

    public function getHandler(): string
    {
        return HttpPatchHandler::class;
    }
}