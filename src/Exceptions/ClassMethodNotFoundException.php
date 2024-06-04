<?php

namespace Codememory\ApiBundle\Exceptions;

use RuntimeException;

class ClassMethodNotFoundException extends RuntimeException
{
    public function __construct(string $class, string $method)
    {
        parent::__construct("Method \"{$method}\" not found in class \"{$class}\"");
    }
}