<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use MongoDB\BSON\UTCDateTime;
use Psr\Clock\ClockInterface;

final readonly class PsrMongoUTCClock implements MongoUTCClock
{
    public function __construct(private ClockInterface $wrapped)
    {
    }

    public function now(): UTCDateTime
    {
        return new UTCDateTime($this->wrapped->now());
    }
}
