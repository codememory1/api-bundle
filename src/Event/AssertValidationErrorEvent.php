<?php

namespace Codememory\ApiBundle\Event;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class AssertValidationErrorEvent
{
    public const string NAME = 'codememory.assert.validation_error';

    public function __construct(
        public readonly mixed $value,
        public readonly ConstraintViolationListInterface $constraintViolationList
    ) {
    }
}