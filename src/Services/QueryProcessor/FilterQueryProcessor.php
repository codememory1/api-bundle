<?php

namespace Codememory\ApiBundle\Services\QueryProcessor;

use Symfony\Component\HttpFoundation\Request;

final class FilterQueryProcessor extends AbstractQueryProcessor
{
    public function getKey(): string
    {
        return 'filter';
    }

    public function getSchema(): string
    {
        return json_encode([
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'properties' => [
                    'key' => [
                        'type' => 'string'
                    ],
                    'value' => [
                        'type' => ['string', 'integer']
                    ]
                ],
                'required' => ['key', 'value']
            ]
        ]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        foreach ($this->all() as $filter) {
            if ($filter['key'] === $key) {
                return $filter['value'];
            }
        }

        return $default;
    }

    public function has(string $key): bool
    {
        foreach ($this->all() as $filter) {
            if ($filter['key'] === $key) {
                return true;
            }
        }

        return false;
    }

    public function validateByFilter(string $key, int $flag): bool
    {
        return $this->has($key) && false !== filter_var($this->get($key), $flag);
    }

    public function validateByRegexp(string $key, string $pattern): bool
    {
        return $this->has($key) && 1 === preg_match($pattern, $this->get($key));
    }

    protected function getData(Request $request): array
    {
        return array_values($request->query->all()[$this->getKey()] ?? []);
    }
}