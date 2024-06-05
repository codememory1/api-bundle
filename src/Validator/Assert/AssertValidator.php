<?php

namespace Codememory\ApiBundle\Validator\Assert;

use Codememory\ApiBundle\Event\AssertValidationErrorEvent;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertErrorHandlerInterface;
use Codememory\ApiBundle\Validator\Assert\Interfaces\AssertValidatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssertValidator implements AssertValidatorInterface
{
    protected bool $isValid = false;
    protected ?AssertErrorHandlerInterface $overriddenErrorHandler = null;
    protected bool $errorHandlerCallEnabled = true;

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly AssertErrorHandlerInterface $mainErrorHandler,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function validate(mixed $value, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): void
    {
        $errors = $this->validator->validate($value, $constraints, $groups);

        $this->isValid = count($errors) > 0;

        $this->errorsHandler($errors);
        $this->dispatchErrorEvent($value, $errors);
    }

    public function setOverriddenErrorHandler(AssertErrorHandlerInterface $handler): self
    {
        $this->overriddenErrorHandler = $handler;

        return $this;
    }

    public function setErrorHandlerCallEnabled(bool $is): AssertValidatorInterface
    {
        $this->errorHandlerCallEnabled = $is;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    private function errorsHandler(ConstraintViolationListInterface $constraintViolationList): void
    {
        if ($this->errorHandlerCallEnabled) {
            ($this->overriddenErrorHandler ?: $this->mainErrorHandler)->handle($constraintViolationList);
        }
    }

    private function dispatchErrorEvent(mixed $value, ConstraintViolationListInterface $constraintViolationList): void
    {
        if (!$this->isValid) {
            $this->eventDispatcher->dispatch(
                new AssertValidationErrorEvent($value, $constraintViolationList),
                AssertValidationErrorEvent::NAME
            );
        }
    }
}