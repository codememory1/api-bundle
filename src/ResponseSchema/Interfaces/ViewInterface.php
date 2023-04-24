<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface ViewInterface
{
    public function isError(): bool;

    public function toArray(): array;
}