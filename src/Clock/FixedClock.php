<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;

class FixedClock implements Clock
{
    public static function fromIso8601(string $timeRepresentation): self
    {
        return new self(new DateTime($timeRepresentation));
    }

    public function __construct(private DateTime $time)
    {
    }

    public function current(): DateTime
    {
        return clone $this->time;
    }

    public function nowIs(DateTime $time): void
    {
        $this->time = $time;
    }
}
