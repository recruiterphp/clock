<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\MockClock;

class SettableClock extends AbstractClock
{
    use SymfonySupport;

    public function __construct(\DateTimeInterface $now)
    {
        $this->wrapped = new MockClock(\DateTimeImmutable::createFromInterface($now));
    }

    public function advance(int|\DateInterval $secondsOrInterval): void
    {
        if (!$secondsOrInterval instanceof \DateInterval) {
            $secondsOrInterval = new \DateInterval("PT{$secondsOrInterval}S");
        }

        $now = $this->now()->add($secondsOrInterval);
        $this->wrapped = new MockClock($now);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now();
    }
}
