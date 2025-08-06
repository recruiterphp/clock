<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class FixedClock implements Clock
{
    use PsrSupport;

    public static function fromIso8601(string $timeRepresentation): self
    {
        return new self(new \DateTime($timeRepresentation));
    }

    public function __construct(private \DateTime $time)
    {
    }

    public function current(): \DateTime
    {
        return clone $this->time;
    }

    public function nowIs(\DateTime $time): void
    {
        $this->time = $time;
    }
}
