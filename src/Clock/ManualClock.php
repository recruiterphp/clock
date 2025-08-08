<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\MockClock;

class ManualClock extends AbstractClock
{
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
        return $this->wrapped->now();
    }

    public function nowIs(\DateTimeInterface $time): void
    {
        $this->wrapped = new MockClock(\DateTimeImmutable::createFromInterface($time));
    }

    public function advance(int|\DateInterval $secondsOrInterval): void
    {
        if (!$secondsOrInterval instanceof \DateInterval) {
            $secondsOrInterval = new \DateInterval("PT{$secondsOrInterval}S");
        }

        $now = $this->now()->add($secondsOrInterval);
        $this->wrapped = new MockClock($now);
    }
}
