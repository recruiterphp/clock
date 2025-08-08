<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class FixedClock implements Clock
{
    use BackwardSupport;
    use SymfonySupport;

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
        return $this->now;
    }

    public function nowIs(\DateTimeInterface $time): void
    {
        $this->now = \DateTimeImmutable::createFromInterface($time);
    }

    /**
     * @throws \DateMalformedStringException
     */
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
