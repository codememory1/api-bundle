<?php

namespace Codememory\ApiBundle\Multithreading;

use function call_user_func;
use Throwable;

final class Fork
{
    public function __construct(
        private readonly Process $process
    ) {
    }

    public function create(): int
    {
        $pid = pcntl_fork();

        if (-1 === $pid) {
            exit('Failed to fork process');
        } else if ($pid) {
            return $pid;
        }

        while (true) {
            $this->process->setPid(getmypid());

            $this->signalListener($this->process);

            if (null !== $this->process->getResolve()) {
                try {
                    call_user_func($this->process->getResolve(), $this->process);
                } catch (Throwable $e) {
                    if (null !== $this->process->getReject()) {
                        call_user_func($this->process->getReject(), $e, $this->process);
                    }
                }
            }

            $this->process->completeByMemory();

            usleep($this->process->options->getDelayBetweenIterationCallbackLogicCall() * 1000);
        }
    }

    public function signalListener(Process $process): void
    {
        foreach ($process->getSignalHandlers() as $signal => $signalHandlers) {
            foreach ($signalHandlers as $signalHandler) {
                pcntl_signal($signal, $signalHandler->handler);
            }
        }
    }
}