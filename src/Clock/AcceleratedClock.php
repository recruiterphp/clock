<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;
use DateInterval;

readonly class AcceleratedClock implements Clock
{
    public function __construct(private DateTime $time)
    {
    }

    public function current(): DateTime
    {
        return clone $this->time;
    }

    public function advance(DateInterval $interval): void
    {
        $this->time->add($interval);
    }
}
