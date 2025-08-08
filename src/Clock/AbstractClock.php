<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;
use Recruiter\MicrotimeClock;
use Recruiter\UTCClock;

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
}
