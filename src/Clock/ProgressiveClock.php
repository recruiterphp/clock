<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\Clock;

readonly class ProgressiveClock implements Clock, ClockInterface
{
    use PsrSupport;

    private \DateTime $current;
    private \DateInterval $defaultInterval;

    public function __construct(?\DateTime $start = null, ?\DateInterval $defaultInterval = null)
    {
        if (null === $start) {
            $start = new \DateTime();
        }
        $this->current = $start;

        if (!$defaultInterval) {
            $this->defaultInterval = new \DateInterval('PT1S');
        } else {
            $this->defaultInterval = $defaultInterval;
        }
    }

    public function current(): \DateTime
    {
        $toReturn = clone $this->current;
        $this->current->add($this->defaultInterval);

        return $toReturn;
    }

    public function forwardInTime(\DateInterval $interval): static
    {
        $this->current->add($interval);

        return $this;
    }
}
