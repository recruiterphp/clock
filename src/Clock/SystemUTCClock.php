<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

class SystemUTCClock implements UTCClock
{
    public function current(): UTCDateTime
    {
        return UTCDateTime::fromMicrotime(microtime());
    }
}
