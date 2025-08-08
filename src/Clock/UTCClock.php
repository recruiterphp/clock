<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;

interface UTCClock
{
    public function now(): UTCDateTime;
}
