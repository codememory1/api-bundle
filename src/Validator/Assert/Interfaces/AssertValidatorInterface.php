<?php

namespace Codememory\ApiBundle\Validator\Assert\Interfaces;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

interface AssertValidatorInterface
{
    public function validate(mixed $value, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): void;

    public function setOverriddenErrorHandler(AssertErrorHandlerInterface $handler): self;

    public function setErrorHandlerCallEnabled(bool $is): self;

    public function isValid(): bool;
}