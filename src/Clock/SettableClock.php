<?php
namespace Recruiter\Clock;

use DateTime;
use DateInterval;
use Recruiter\Clock;

class SettableClock implements Clock
{
    public function __construct(private readonly DateTime $current)
    {
    }

    public function advance($seconds)
    {
        $this->current->add(new DateInterval("PT{$seconds}S"));
    }

    public function current()
    {
        return $this->current;
    }
}
