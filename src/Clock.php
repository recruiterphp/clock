<?php

declare(strict_types=1);

namespace Recruiter;

use Symfony\Component\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    public function asUTC(): UTCClock;

    public function asMicrotime(): MicrotimeClock;
}
