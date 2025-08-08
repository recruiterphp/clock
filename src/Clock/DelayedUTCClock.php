<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

readonly class DelayedUTCClock implements UTCClock
{
    public function __construct(private UTCClock $originalClock, private int $delayInSeconds)
    {
    }

    public function now(): UTCDateTime
    {
        return $this
            ->originalClock
            ->now()
            ->subtractSeconds($this->delayInSeconds)
        ;
    }
}
