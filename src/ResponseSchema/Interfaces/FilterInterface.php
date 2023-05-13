<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface FilterInterface
{
    public function getLabel(): string;

    public function getKey(): string|array;

    public function getElement(): string;

    public function getExtra(): array;
}