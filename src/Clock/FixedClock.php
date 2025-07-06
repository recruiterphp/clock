<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;

class FixedClock implements Clock
{
    public static function fromIso8601($timeRepresentation)
    {
        return new self(new DateTime($timeRepresentation));
    }

    public function __construct(private DateTime $time)
    {
    }

    /**
     * @return DateTime
     */
    public function current()
    {
        return clone $this->time;
    }

    public function nowIs(DateTime $time)
    {
        $this->time = $time;
    }
}
