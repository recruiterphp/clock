<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

use Psr\Clock\ClockInterface;
use Recruiter\StopWatch;

class ClockStopWatch implements StopWatch
{
    private ?\DateTimeImmutable $start = null;

    public function __construct(private readonly ClockInterface $clock)
    {
    }

    public function start(): void
    {
        $this->start = $this->clock->now();
    }

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedSeconds(): float
    {
        if (!$this->start) {
            throw new StopWatchNotStartedException();
        }

        $now = $this->clock->now();

        return (float) $now->diff($this->start)->s;
    }

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedMilliseconds(): float
    {
        return $this->elapsedSeconds() * 1000;
    }

    public function elapsedMicroseconds(): float
    {
        return $this->elapsedMilliseconds() * 1000;
    }
}
