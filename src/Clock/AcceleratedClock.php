<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\Clock;

readonly class AcceleratedClock implements Clock, ClockInterface
{
    use PsrSupport;

    public function __construct(private \DateTime $time)
    {
    }

    public function current(): \DateTime
    {
        return clone $this->time;
    }

    public function advance(\DateInterval $interval): void
    {
        $this->time->add($interval);
    }
}
