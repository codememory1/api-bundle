<?php

namespace Codememory\ApiBundle\ResponseSchema\Meta;

use Codememory\ApiBundle\ResponseSchema\Interfaces\MetaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\SortInterface;

final readonly class SortMeta implements MetaInterface
{
    /**
     * @param array<int, SortInterface> $sorts
     */
    public function __construct(
        private array $sorts
    ) {
    }

    public function getKey(): string
    {
        return 'sorts';
    }

    public function toArray(): array
    {
        return array_map(static function(SortInterface $sort) {
            return [
                'key' => $sort->getKey(),
                'label' => $sort->getLabel()
            ];
        }, $this->sorts);
    }
}