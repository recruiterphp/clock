<?php

declare(strict_types=1);

namespace Recruiter\Clock;

trait SymfonySupport
{
    private \DateTimeImmutable $now;

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
