<?php
namespace RecruiterPhp\Clock;
use RecruiterPhp\MicrotimeClock;

class SystemMicrotimeClock implements MicrotimeClock
{
    /**
     * @return float  e.g. 1300000000.234567
     */
    public function current()
    {
        return microtime(true);
    }
}
