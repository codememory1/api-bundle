<?php

namespace Codememory\ApiBundle\Validator\Assert\Interfaces;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface AssertErrorHandlerInterface
{
    public function handle(ConstraintViolationListInterface $constraintViolationList): void;
}