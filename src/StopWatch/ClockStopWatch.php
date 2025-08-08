<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

use Psr\Clock\ClockInterface;

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
        return $this->elapsedMicroseconds() / 1_000_000;
    }

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedMilliseconds(): float
    {
        return $this->elapsedMicroseconds() / 1_000;
    }

    public function elapsedMicroseconds(): float
    {
        if (!$this->start) {
            throw new StopWatchNotStartedException();
        }

        $now = $this->clock->now();

        return $now->format('Uu') - (float) $this->start->format('Uu');
    }
}
