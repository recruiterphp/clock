<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class ProgressiveClock implements Clock
{
    use BackwardSupport;

    private \DateTimeImmutable $now;
    private readonly \DateInterval $defaultInterval;

    public function __construct(?\DateTimeInterface $start = null, ?\DateInterval $defaultInterval = null)
    {
        if (null === $start) {
            $start = new \DateTimeImmutable();
        }
        $this->now = \DateTimeImmutable::createFromInterface($start);

        if (!$defaultInterval) {
            $this->defaultInterval = new \DateInterval('PT1S');
        } else {
            $this->defaultInterval = $defaultInterval;
        }
    }

    public function now(): \DateTimeImmutable
    {
        $toReturn = $this->now;

        $this->now = $this->now->add($this->defaultInterval);

        return $toReturn;
    }

    /**
     * @return $this
     */
    public function forwardInTime(\DateInterval $interval): static
    {
        $this->now=$this->now->add($interval);

        return $this;
    }
}
