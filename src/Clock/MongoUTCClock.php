<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use MongoDB\BSON\UTCDateTime;

interface MongoUTCClock
{
    public function now(): UTCDateTime;
}
