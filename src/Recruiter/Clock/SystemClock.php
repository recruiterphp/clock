<?php
namespace Recruiter\Clock;
use Recruiter\Clock;
use DateTime;

class SystemClock implements Clock
{
    /**
     * @return DateTime
     */
    public function current()
    {
        return new DateTime();
    }
}
