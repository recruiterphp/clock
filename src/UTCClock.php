<?php

declare(strict_types=1);

namespace Recruiter;

use Recruiter\DateTime\UTCDateTime;

interface UTCClock
{
    public function now(): UTCDateTime;
}
