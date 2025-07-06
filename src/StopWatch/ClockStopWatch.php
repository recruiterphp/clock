<?php
namespace Recruiter\StopWatch;

use Recruiter\Clock;
use Recruiter\StopWatch;

class ClockStopWatch implements StopWatch
{
    private ?\DateTime $start = null;

    public function __construct(private readonly Clock $clock)
    {
    }

    public function start(): void
    {
        $this->start = $this->clock->current();
    }

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedSeconds(): float
    {
        if (!$this->start) {
            throw new StopWatchNotStartedException();
        }

        $now = $this->clock->current();
        return (float)$now->diff($this->start)->s;
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
