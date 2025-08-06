<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class AcceleratedClock implements Clock
{
    use BackwardSupport;

    private \DateTimeImmutable $now;

    public function __construct(\DateTimeInterface $now)
    {
        $this->now = \DateTimeImmutable::createFromInterface($now);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }

    public function advance(\DateInterval $interval): void
    {
        $this->now = $this->now->add($interval);
    }
}
