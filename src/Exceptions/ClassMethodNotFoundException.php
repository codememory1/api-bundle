<?php

namespace Codememory\ApiBundle\Exceptions;

use RuntimeException;
use Throwable;

class ClassMethodNotFoundException extends RuntimeException
{
    public function __construct(
        public readonly string $class,
        public readonly string $method,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("Method \"{$method}\" not found in class \"{$class}\"", $code, $previous);
    }
}