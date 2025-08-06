<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class FixedClock implements Clock
{
    use BackwardSupport;

    private \DateTimeImmutable $now;

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
        return clone $this->now;
    }

    public function nowIs(\DateTimeInterface $time): void
    {
        $this->now = \DateTimeImmutable::createFromInterface($time);
    }
}
