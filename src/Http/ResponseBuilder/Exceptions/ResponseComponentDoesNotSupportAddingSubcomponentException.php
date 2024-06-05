<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Exceptions;

use LogicException;

class ResponseComponentDoesNotSupportAddingSubcomponentException extends LogicException
{
    public function __construct(string $component)
    {
        parent::__construct("Component \"{$component}\" does not support adding subcomponents");
    }
}