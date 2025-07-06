<?php
namespace Recruiter\Clock;

use Recruiter\Clock;
use Recruiter\UTCClock;

class DelayedUTCClock implements UTCClock
{
    public function __construct(private readonly UTCClock $originalClock, private $delayInSeconds)
    {
    }

    public function current()
    {
        return $this
            ->originalClock
            ->current()
            ->subtractSeconds($this->delayInSeconds)
        ;
    }
}
