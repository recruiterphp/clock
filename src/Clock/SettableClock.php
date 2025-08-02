<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\Clock;

class SettableClock implements Clock, ClockInterface
{
    use PsrSupport;

    public function __construct(private readonly \DateTime $current)
    {
    }

    public function advance(int $seconds): void
    {
        $this->current->add(new \DateInterval("PT{$seconds}S"));
    }

    public function current(): \DateTime
    {
        return $this->current;
    }
}
