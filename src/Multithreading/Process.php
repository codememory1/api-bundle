<?php

namespace Codememory\ApiBundle\Multithreading;

use Closure;
use Ramsey\Uuid\Uuid;
use const SIGCHLD;
use const SIGINT;
use const SIGQUIT;
use const SIGTSTP;

final class Process
{
    public readonly string $id;
    private ?int $pid = null;
    private ?Closure $resolve = null;
    private ?Closure $reject = null;
    private array $signalHandlers = [];

    public function __construct(
        public readonly ProcessOptions $options
    ) {
        $this->id = Uuid::uuid4()->toString();

        $this->stopSignal();
        $this->restartSignal();
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getResolve(): ?callable
    {
        return $this->resolve;
    }

    public function setResolve(callable $callback): self
    {
        $this->resolve = $callback;

        return $this;
    }

    public function getReject(): ?callable
    {
        return $this->reject;
    }

    public function setReject(callable $reject): self
    {
        $this->reject = $reject;

        return $this;
    }

    public function addSignalHandler(int $signal, callable $handler): self
    {
        $this->signalHandlers[$signal][] = new SignalHandler($handler);

        return $this;
    }

    /**
     * @return array<int, array<int, SignalHandler>>
     */
    public function getSignalHandlers(): array
    {
        return $this->signalHandlers;
    }

    public function sendSignal(int $signal): void
    {
        posix_kill($this->pid, $signal);
    }

    public function completeByMemory(): void
    {
        if (memory_get_usage() / 1024 / 1024 > $this->options->getMaxMemoryUsage() / 1024 / 1024) {
            $this->sendSignal(SIGCHLD);
        }
    }

    private function restartSignal(): void
    {
        $this->addSignalHandler(SIGCHLD, static function(): void {
            exit;
        });
    }

    private function stopSignal(): void
    {
        $handler = static function(): void {
            exit;
        };

        $this->addSignalHandler(SIGINT, $handler);
        $this->addSignalHandler(SIGQUIT, $handler);
        $this->addSignalHandler(SIGTSTP, $handler);
    }
}