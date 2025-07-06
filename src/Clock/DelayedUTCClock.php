<?php
namespace Recruiter\Clock;

use Recruiter\Clock;
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
