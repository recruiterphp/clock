<?php
namespace RecruiterPhp\Clock;
use RecruiterPhp\Clock;
use DateTime;

class SystemClock implements Clock
{
    /**
     * @return DateTime
     */
    public function current()
    {
        return new DateTime();
    }
}
