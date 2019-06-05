<?php
namespace RecruiterPhp\Clock;

use RecruiterPhp\Clock;
use RecruiterPhp\UTCClock;

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
