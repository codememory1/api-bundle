<?php

namespace Codememory\ApiBundle\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

final class DuplicateTaggedServiceException extends Exception
{
    #[Pure]
    public function __construct(string $tag, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Unable to register multiple services with tag {$tag}", $code, $previous);
    }
}