<?php

namespace Codememory\ApiBundle\Multithreading;

final class WorkerOptions
{
    public function __construct(
        private int $delayBetweenParentIteration = 100 // 100ms
    ) {
    }

    public function getDelayBetweenParentIteration(): int
    {
        return $this->delayBetweenParentIteration;
    }

    public function setDelayBetweenParentIteration(int $ms): self
    {
        $this->delayBetweenParentIteration = $ms;

        return $this;
    }
}