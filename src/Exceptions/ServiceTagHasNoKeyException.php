<?php

namespace Codememory\ApiBundle\Exceptions;

use Exception;
use Throwable;

final class ServiceTagHasNoKeyException extends Exception
{
    public function __construct(string $tag, string $serviceId, string $expectKey, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The {$tag} tag of the {$serviceId} service must have the {$expectKey} key", $code, $previous);
    }
}