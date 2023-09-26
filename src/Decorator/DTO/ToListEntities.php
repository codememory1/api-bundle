<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class ToListEntities implements DecoratorInterface
{
    public function __construct(
        public string $entity,
        public string $column = 'id',
        public bool $unique = true,
        public ?string $whereCallback = null
    ) {
    }

    public function getHandler(): string
    {
        return ToListEntitiesHandler::class;
    }
}