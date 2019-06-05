<?php
namespace Recruiter\Clock;

use Recruiter\Clock;
use Recruiter\UTCClock;

class DelayedUTCClock implements UTCClock
{
    private $originalClock;
    private $delayInSeconds;

    public function __construct(UTCClock $originalClock, $delayInSeconds)
    {
        $this->originalClock = $originalClock;
        $this->delayInSeconds = $delayInSeconds;
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
