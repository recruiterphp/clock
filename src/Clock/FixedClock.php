<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\MockClock;

class FixedClock extends AbstractClock
{
    private MockClock $wrapped;

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromIso8601(string $timeRepresentation): self
    {
        return new self(new \DateTimeImmutable($timeRepresentation));
    }

    public function __construct(\DateTimeInterface $time)
    {
        $this->nowIs($time);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now();
    }

    public function nowIs(\DateTimeInterface $time): void
    {
        $this->wrapped = new MockClock(\DateTimeImmutable::createFromInterface($time));
    }

    public function sleep(float|int $seconds): void
    {
        $this->wrapped->sleep($seconds);
    }

    /**
     * @throws \DateInvalidTimeZoneException
     */
    public function withTimeZone(\DateTimeZone|string $timezone): static
    {
        $clone = clone $this;
        $clone->wrapped = $this->wrapped->withTimeZone($timezone);

        return $clone;
    }
}
