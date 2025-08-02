<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\Clock;

class SystemClock implements Clock, ClockInterface
{
    use PsrSupport;

    public function current(): \DateTime
    {
        return new \DateTime();
    }
}
