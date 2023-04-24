<?php

namespace Codememory\ApiBundle\Services\QueryProcessor;

final class SortQueryProcessor extends AbstractQueryProcessor
{
    public function getKey(): string
    {
        return 'sort';
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
                        'type' => 'string',
                        'enum' => ['ASC', 'DESC']
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
}