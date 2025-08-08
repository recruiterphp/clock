<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\NativeClock;

class SystemClock extends AbstractClock
{
    private NativeClock $wrapped;

    /**
     * @throws \DateInvalidTimeZoneException
     */
    public function __construct(\DateTimeZone|string|null $timezone = null)
    {
        $this->wrapped = new NativeClock($timezone);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now();
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
