<?php

namespace Codememory\ApiBundle\Exceptions;

use RuntimeException;
use Throwable;

final class ServiceTagHasNoKeyException extends RuntimeException
{
    public function __construct(
        public readonly string $tag,
        public readonly string $serviceId,
        public readonly string $expectKey,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("The {$tag} tag of the {$serviceId} service must have the {$expectKey} key", $code, $previous);
    }
}