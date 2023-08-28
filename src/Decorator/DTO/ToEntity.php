<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class ToEntity implements DecoratorInterface
{
    public function __construct(
        public string $column = 'id',
        public ?string $whereCallback = null,
        public ?string $entityNotFoundCallback = null
    ) {
    }

    public function getHandler(): string
    {
        return ToEntityHandler::class;
    }
}