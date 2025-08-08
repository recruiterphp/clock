<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\MockClock;

class AcceleratedClock extends AbstractClock
{
    use SymfonySupport;

    public function __construct(\DateTimeInterface $now)
    {
        $now = \DateTimeImmutable::createFromInterface($now);
        $this->wrapped = new MockClock($now);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now();
    }

    public function advance(\DateInterval $interval): void
    {
        $this->wrapped = new MockClock($this->now()->add($interval));
    }
}
