<?php

namespace Codememory\ApiBundle\Validator\JsonSchema\Interfaces;

interface JsonSchemaValidatorInterface
{
    public function validate(array $data, string $schema, ?int $mode = null): self;

    public function isValidated(): bool;

    public function getErrors(): array;
}