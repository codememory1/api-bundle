<?php

namespace Codememory\ApiBundle\Exceptions;

use JetBrains\PhpStorm\Pure;
use LogicException;
use Throwable;

final class DoesNotImplementException extends LogicException
{
    #[Pure]
    public function __construct(
        public readonly string $class,
        public readonly string $expectImplement,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("The {$class} class must implement the {$expectImplement} interface", $code, $previous);
    }
}