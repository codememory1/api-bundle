<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Meta;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;

class PaginationSubcomponent extends AbstractComponent implements ResponseSubcomponentInterface
{
    public const string NAME = 'pagination';
    protected array $value;

    public function __construct(
        private readonly int $totalPages,
        private readonly int $currentPage,
        private readonly int $totalRecords
    ) {
        $this->value = [
            'total_pages' => $this->totalPages,
            'current_page' => $this->currentPage,
            'total_records' => $this->totalRecords
        ];
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}