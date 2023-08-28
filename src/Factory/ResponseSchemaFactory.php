<?php

namespace Codememory\ApiBundle\Factory;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaFactoryInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\ResponseSchema;

final class ResponseSchemaFactory implements ResponseSchemaFactoryInterface
{
    public function createResponseSchema(): ResponseSchemaInterface
    {
        return new ResponseSchema();
    }
}