<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\MockClock;

class ProgressiveClock extends AbstractClock
{
    use SymfonySupport;
    private readonly \DateInterval $defaultInterval;

    public function __construct(?\DateTimeInterface $start = null, ?\DateInterval $defaultInterval = null)
    {
        $now = $start ? \DateTimeImmutable::createFromInterface($start) : new \DateTimeImmutable();
        $this->wrapped = new MockClock($now);
        $this->defaultInterval = $defaultInterval ?? new \DateInterval('PT1S');
    }

    public function now(): \DateTimeImmutable
    {
        $now = $this->wrapped->now();

        $this->forwardInTime($this->defaultInterval);

        return $now;
    }

    /**
     * @return $this
     */
    public function forwardInTime(\DateInterval $interval): static
    {
        $newTime = $this->wrapped->now()->add($interval);
        $this->wrapped = new MockClock($newTime);

        return $this;
    }
}
