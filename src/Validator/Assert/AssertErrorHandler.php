<?php

namespace Codememory\ApiBundle\Validator\Assert;

use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertErrorHandlerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AssertErrorHandler implements AssertErrorHandlerInterface
{
    /**
     * @throws HttpException
     */
    public function handle(ConstraintViolationListInterface $constraintViolationList): void
    {
        foreach ($constraintViolationList as $constraintViolation) {
            throw new HttpException(422, $constraintViolation->getMessage());
        }
    }
}