<?php

namespace Codememory\ApiBundle\Validator\JsonSchema;

use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

class JsonSchemaValidator
{
    protected bool $isValidated = false;
    protected array $errors = [];

    public function validate(array $data, string $schema, ?int $mode = null): self
    {
        $uriRetriever = new UriRetriever();
        $schemaStorage = new SchemaStorage($uriRetriever, new UriResolver());
        $jsonSchemaValidator = new Validator(new Factory($schemaStorage, $uriRetriever));
        $data = json_decode(json_encode($data), false);

        $jsonSchemaValidator->validate($data, json_decode($schema, false), $mode);

        $this->isValidated = $jsonSchemaValidator->isValid();

        $this->buildErrors($schema, $jsonSchemaValidator->getErrors());

        return $this;
    }

    public function isValidated(): bool
    {
        return $this->isValidated;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function buildErrors(string $schema, array $errors): void
    {
        $schema = json_decode($schema, true);

        foreach ($errors as $error) {
            $validationError = new ValidationError($error);

            $this->errors[$validationError->getProperty()][] = $this->getErrorMessage($schema, $validationError);
        }
    }

    private function getErrorMessage(array $schema, ValidationError $validationError): ?string
    {
        $messages = $schema['messages'] ?? [];

        if (null === $validationError->getProperty()
            || null === $validationError->getConstraint()
            || !array_key_exists($validationError->getProperty(), $messages)
            || !array_key_exists($validationError->getConstraint(), $messages[$validationError->getProperty()])) {
            return $validationError->getMessage();
        }

        $message = $messages[$validationError->getProperty()][$validationError->getConstraint()];

        if (!$validationError->existConstraintValue()) {
            return $message;
        }

        return str_replace('%value%', $validationError->getConstraintValue(), $message);
    }
}