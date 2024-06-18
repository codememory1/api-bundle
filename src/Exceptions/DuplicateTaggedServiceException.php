<?php

namespace Codememory\ApiBundle\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

final class DuplicateTaggedServiceException extends RuntimeException
{
    #[Pure]
    public function __construct(
        public readonly string $tag,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("Unable to register multiple services with tag {$tag}", $code, $previous);
    }
}