<?php

namespace Codememory\ApiBundle\QueryProcessor;

final class PaginationQueryProcessor extends AbstractQueryProcessor
{
    public function getKey(): string
    {
        return 'pagination';
    }

    public function getSchema(): string
    {
        return json_encode([
            'type' => 'object',
            'properties' => [
                'page' => [
                    'type' => ['integer', 'string'],
                    'pattern' => '^[0-9]+$'
                ],
                'limit' => [
                    'type' => ['integer', 'string'],
                    'pattern' => '^[0-9]+$'
                ]
            ]
        ]);
    }

    public function getPage(): int
    {
        return $this->get('page') ?: 0;
    }

    public function getLimit(): int
    {
        return $this->get('limit') ?: 0;
    }
}