<?php

namespace Codememory\ApiBundle\ResponseSchema\Interfaces;

interface ResponseSchemaFactoryInterface
{
    public function createResponseSchema(): ResponseSchemaInterface;
}