<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\ClockInterface;

class SettableClock extends AbstractClock
{
    private ?ManualClock $fixed = null;

    public function __construct(private ClockInterface $wrapped)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->enabledClock()->now();
    }

    public function nowIs(\DateTimeImmutable $fixed): void
    {
        $this->fixed = new ManualClock($fixed);
    }

    public function elapse(\DateInterval $amount): \DateTimeImmutable
    {
        $this->nowIs($this->now()->add($amount));

        return $this->now();
    }

    public function reset(): void
    {
        $this->fixed = null;
    }

    public function sleep(float|int $seconds): void
    {
        $this->enabledClock()->sleep($seconds);
    }

    private function enabledClock(): ClockInterface
    {
        return $this->fixed ?? $this->wrapped;
    }

    /**
     * @throws \DateInvalidTimeZoneException
     */
    public function withTimeZone(\DateTimeZone|string $timezone): static
    {
        $clone = clone $this;

        $clone->wrapped = $this->wrapped->withTimeZone($timezone);
        if ($clone->fixed) {
            $clone->fixed = $clone->fixed->withTimeZone($timezone);
        }

        return $clone;
    }
}
