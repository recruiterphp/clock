<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class SystemClock implements Clock
{
    use PsrSupport;

    public function current(): \DateTime
    {
        return new \DateTime();
    }
}
