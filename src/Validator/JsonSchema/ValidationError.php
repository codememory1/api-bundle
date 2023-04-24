<?php

namespace Codememory\ApiBundle\Validator\JsonSchema;

final class ValidationError
{
    public function __construct(
        private readonly array $error
    ) {
    }

    public function getProperty(): ?string
    {
        return $this->error['property'] ?? null;
    }

    public function getPointer(): ?string
    {
        return $this->error['pointer'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->error['message'] ?? null;
    }

    public function getConstraint(): ?string
    {
        return $this->error['constraint'] ?? null;
    }

    public function getContext(): ?int
    {
        return $this->error['context'];
    }

    public function existConstraintValue(): bool
    {
        return array_key_exists($this->getConstraint(), $this->error);
    }

    public function getConstraintValue(): mixed
    {
        $constraint = $this->getConstraint();

        if (null === $constraint) {
            return null;
        }

        return $this->error[$constraint] ?? null;
    }
}