<?php

namespace Codememory\ApiBundle\Validator\Assert;

use Codememory\ApiBundle\Exceptions\HttpException;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AssertValidator implements AssertValidatorInterface
{
    private bool $isValid = false;

    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @throws HttpException
     */
    public function validate(object $object, bool $throw = true): void
    {
        $errors = $this->validator->validate($object);

        $this->isValid = count($errors) > 0;

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            if ($throw) {
                $this->exception($error);
            }
        }
    }

    /**
     * @throws HttpException
     */
    private function exception(ConstraintViolation $violation): void
    {
        $httpCode = $violation->getConstraint()->payload['http'] ?? 400;
        $platformCode = $violation->getConstraint()->payload['platform'] ?? -1;

        throw new HttpException($httpCode, $platformCode, $violation->getMessage());
    }
}