<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\StopWatch\ClockStopWatch;

abstract class AbstractClock implements Clock
{
    public function asUTC(): UTCClock
    {
        return new PsrUTCClock($this);
    }

    public function asMicrotime(): MicrotimeClock
    {
        return new PsrMicrotimeClock($this);
    }

    public function stopWatch(): ClockStopWatch
    {
        return new ClockStopWatch($this);
    }
}
