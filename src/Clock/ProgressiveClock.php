<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class ProgressiveClock implements Clock
{
    use BackwardSupport;
    use SymfonySupport;
    private readonly \DateInterval $defaultInterval;

    public function __construct(?\DateTimeInterface $start = null, ?\DateInterval $defaultInterval = null)
    {
        $this->now = $start ? \DateTimeImmutable::createFromInterface($start) : new \DateTimeImmutable();
        $this->defaultInterval = $defaultInterval ?? new \DateInterval('PT1S');
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
        $this->now = $this->now->add($interval);

        return $this;
    }

    public function sleep(float|int $seconds): void
    {
        $this->now = $this->now->modify(sprintf('+%f seconds', $seconds));
    }

    public function withTimeZone(\DateTimeZone|string $timezone): static
    {
        if (\is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        $clone = clone $this;
        $clone->now = $clone->now->setTimezone($timezone);

        return $clone;
    }
}
