<?php

namespace Codememory\ApiBundle\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

class FileNotFoundException extends RuntimeException
{
    #[Pure]
    public function __construct(
        public readonly string $path,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("File path {$path} not found", $code, $previous);
    }
}