<?php

namespace Codememory\ApiBundle\Multithreading;

use function call_user_func;
use JetBrains\PhpStorm\NoReturn;
use const WNOHANG;

class ProcessManager
{
    /**
     * @var array<int, Process>
     */
    private array $processes = [];

    /**
     * @var array<string, int>
     */
    private array $activatedProcesses = [];

    public function __construct(
        public readonly WorkerOptions $workerOptions,
        public readonly ProcessOptions $processOptions
    ) {
    }

    public function add(callable $callback): self
    {
        $process = new Process($this->processOptions);

        call_user_func($callback, $process, count($this->processes) + 1);

        $this->processes[] = $process;

        return $this;
    }

    public function processIsActivated(Process $process): bool
    {
        return array_key_exists($process->id, $this->activatedProcesses);
    }

    public function activateProcess(Process $process): void
    {
        $fork = new Fork($process);

        $this->activatedProcesses[$process->id] = $fork->create();
    }

    #[NoReturn]
    public function start(): void
    {
        while (true) {
            foreach ($this->activatedProcesses as $id => $pid) {
                $result = pcntl_waitpid($pid, $status, WNOHANG);

                if (-1 === $result || 0 < $result) {
                    unset($this->activatedProcesses[$id]);
                }
            }

            foreach ($this->processes as $process) {
                if (!$this->processIsActivated($process)) {
                    $this->activateProcess($process);
                }
            }

            usleep($this->workerOptions->getDelayBetweenParentIteration() * 1000);
        }
    }
}