<?php

namespace Codememory\ApiBundle\Validator\Assert;

use function call_user_func;
use Codememory\ApiBundle\Exceptions\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AssertValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly array $config
    ) {
    }

    /**
     * @throws HttpException
     */
    public function validate(object $object, ?callable $callback = null): void
    {
        $errors = $this->validator->validate($object);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            if (null !== $callback) {
                call_user_func($callback, $error);
            } else {
                $this->exception($error);
            }
        }
    }

    /**
     * @throws HttpException
     */
    private function exception(ConstraintViolation $violation): void
    {
        $httpCode = $violation->getConstraint()->payload['http'] ?? $this->config['default_http_code'];
        $platformCode = $violation->getConstraint()->payload['platform'] ?? $this->config['default_platform_code'];

        throw new HttpException($httpCode, $platformCode, $violation->getMessage());
    }
}