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

    public function advance($seconds): void
    {
        $this->current->add(new DateInterval("PT{$seconds}S"));
    }

    public function current(): DateTime
    {
        return $this->current;
    }
}
