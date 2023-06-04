<?php

namespace Codememory\ApiBundle\Multithreading;

final class ProcessOptions
{
    public function __construct(
        private int $delayBetweenIterationCallbackLogicCall = 100, // 100ms
        private int $maxMemoryUsage = 100 * (1024 * 1024) // 100MB
    ) {
    }

    public function getDelayBetweenIterationCallbackLogicCall(): int
    {
        return $this->delayBetweenIterationCallbackLogicCall;
    }

    public function setDelayBetweenIterationCallbackLogicCall(int $ms): self
    {
        $this->delayBetweenIterationCallbackLogicCall = $ms;

        return $this;
    }

    public function getMaxMemoryUsage(): int
    {
        return $this->maxMemoryUsage;
    }

    public function setMaxMemoryUsage(int $mb): self
    {
        $this->maxMemoryUsage = $mb;

        return $this;
    }
}