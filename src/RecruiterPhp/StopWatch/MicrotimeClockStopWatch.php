<?php
namespace RecruiterPhp\StopWatch;

use RecruiterPhp\MicrotimeClock;
use RecruiterPhp\StopWatch;

class MicrotimeClockStopWatch implements StopWatch
{
    private $clock;
    private $start;
    private $elapsed;

    public function __construct(MicrotimeClock $clock)
    {
        $this->clock = $clock;
    }

    public function start()
    {
        $this->start = $this->clock->current();
    }

    /**
     * @return float
     */
    public function elapsedSeconds()
    {
        if (!$this->start) {
            throw new StopWatchNotStartedException();
        }

        $now = $this->clock->current();
        return $now - $this->start;
    }

    /**
     * @return float
     */
    public function elapsedMilliseconds()
    {
        return $this->elapsedSeconds() * 1000;
    }

    /**
     * @return float
     */
    public function elapsedMicroseconds()
    {
        return $this->elapsedMilliseconds() * 1000;
    }
}
