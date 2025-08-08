<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\StopWatch\StopWatch;
use Symfony\Component\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    public function asUTC(): UTCClock;

    public function asMicrotime(): MicrotimeClock;

    public function stopWatch(): StopWatch;
}
