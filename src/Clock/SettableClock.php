<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class SettableClock implements Clock
{
    use BackwardSupport;
    use SymfonySupport;

    public function __construct(\DateTimeInterface $now)
    {
        $this->now = \DateTimeImmutable::createFromInterface($now);
    }

    public function advance(int $seconds): void
    {
        $this->now = $this->now->add(new \DateInterval("PT{$seconds}S"));
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
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
