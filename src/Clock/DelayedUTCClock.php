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

    public function current(): UTCDateTime
    {
        return $this
            ->originalClock
            ->current()
            ->subtractSeconds($this->delayInSeconds)
        ;
    }
}
