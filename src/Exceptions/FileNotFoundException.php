<?php

namespace Codememory\ApiBundle\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class FileNotFoundException extends Exception
{
    #[Pure]
    public function __construct(string $path, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("File path {$path} not found", $code, $previous);
    }
}