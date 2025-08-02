<?php
namespace Recruiter\Clock;
use Psr\Clock\ClockInterface;
use Recruiter\Clock;
use DateTime;

class SystemClock implements Clock, ClockInterface
{
    use PsrSupport;

    public function current(): DateTime
    {
        return new DateTime();
    }
}
