<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;
use DateInterval;

class AcceleratedClock implements Clock
{
    public function __construct(private readonly DateTime $time)
    {
    }

    /**
     * @return DateTime
     */
    public function current()
    {
        return clone $this->time;
    }

    public function advance(DateInterval $interval)
    {
        $this->time->add($interval);
    }
}
