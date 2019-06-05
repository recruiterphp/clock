<?php
namespace RecruiterPhp\Clock;

use RecruiterPhp\DateTime\UTCDateTime;
use RecruiterPhp\UTCClock;

class SystemUTCClock implements UTCClock
{
    public function current()
    {
        return UTCDateTime::fromMicrotime(microtime());
    }
}
