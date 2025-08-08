<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;
use Symfony\Component\Clock\NativeClock;

class SystemClock implements Clock
{
    use BackwardSupport;

    private NativeClock $wrapped;

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

    public function withTimeZone(\DateTimeZone|string $timezone): static
    {
        $clone = clone $this;
        $clone->wrapped = $this->wrapped->withTimeZone($timezone);

        return $clone;
    }
}
