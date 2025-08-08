<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\DateTime\UTCDateTime;

final readonly class PsrUTCClock implements UTCClock
{
    public function __construct(private ClockInterface $wrapped)
    {
    }

    public function now(): UTCDateTime
    {
        return UTCDateTime::box($this->wrapped->now());
    }
}
