<?php

namespace Codememory\ApiBundle\Multithreading;

use Closure;

final readonly class SignalHandler
{
    public function __construct(
        public ?Closure $handler
    ) {
    }
}