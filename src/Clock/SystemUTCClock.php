<?php
namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

class SystemUTCClock implements UTCClock
{
    public function current()
    {
        return UTCDateTime::fromMicrotime(microtime());
    }
}
