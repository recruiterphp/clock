<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

use Recruiter\MicrotimeClock;
use Recruiter\StopWatch;

class MicrotimeClockStopWatch implements StopWatch
{
    private ?float $start = null;

    public function __construct(private readonly MicrotimeClock $clock)
    {
    }

    public function start(): void
    {
        $this->start = $this->clock->current();
    }

    public function elapsedSeconds(): float
    {
        if (!$this->start) {
            throw new StopWatchNotStartedException();
        }

        $now = $this->clock->current();

        return $now - $this->start;
    }

    public function elapsedMilliseconds(): float
    {
        return $this->elapsedSeconds() * 1000;
    }

    public function elapsedMicroseconds(): float
    {
        return $this->elapsedMilliseconds() * 1000;
    }
}
