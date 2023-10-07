<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class FileExpected implements DecoratorInterface
{
    public function __construct(
        public bool $multiple = false
    ) {
    }

    public function getHandler(): string
    {
        return FileExpectedHandler::class;
    }
}