<?php

namespace Codememory\ApiBundle\QueryProcessor;

use Codememory\ApiBundle\Validator\JsonSchema\JsonSchemaValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractQueryProcessor
{
    private array $errors = [];
    private ?array $query = null;

    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly JsonSchemaValidator $jsonSchemaValidator
    ) {
    }

    abstract public function getKey(): string;

    abstract public function getSchema(): string;

    protected function getData(Request $request): array
    {
        return $request->query->all()[$this->getKey()] ?? [];
    }

    public function getQuery(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = $this->getData($request);

        $this->jsonSchemaValidator->validate($data, $this->getSchema());

        if (!$this->jsonSchemaValidator->isValidated()) {
            $this->errors = $this->jsonSchemaValidator->getErrors();

            return [];
        }

        return $data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function all(): array
    {
        if (null === $this->query) {
            $this->query = $this->getQuery();
        }

        return $this->query;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }
}