<?php

namespace Codememory\ApiBundle\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

final class DoesNotImplementException extends Exception
{
    #[Pure]
    public function __construct(string $class, string $expectImplement, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The {$class} class must implement the {$expectImplement} interface", $code, $previous);
    }
}