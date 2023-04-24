<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface SortInterface
{
    public function getLabel(): string;

    public function getKey(): string;
}