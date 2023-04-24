<?php

namespace Codememory\ApiBundle\Multithreading;

use Closure;

final class SignalHandler
{
    public function __construct(
        public readonly ?Closure $handler
    ) {
    }
}