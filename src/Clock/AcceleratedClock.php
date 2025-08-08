<?php

declare(strict_types=1);

namespace Recruiter\Clock;

class AcceleratedClock extends AbstractClock
{
    use SymfonySupport;

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

    public function sleep(float|int $seconds): void
    {
        $this->now = $this->now->modify(sprintf('+%f seconds', $seconds));
    }

    /**
     * @throws \DateInvalidTimeZoneException
     */
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
