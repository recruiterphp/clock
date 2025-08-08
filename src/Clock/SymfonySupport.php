<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\ClockInterface;

trait SymfonySupport
{
    private ClockInterface $wrapped;

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
