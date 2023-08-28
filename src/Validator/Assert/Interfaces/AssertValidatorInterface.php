<?php

namespace Codememory\ApiBundle\Validator\Assert\Interfaces;

interface AssertValidatorInterface
{
    public function validate(object $object, bool $throw = true): void;

    public function isValid(): bool;
}