<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;

class SystemClock implements Clock
{
    public function current(): DateTime
    {
        return new DateTime();
    }
}
