<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\Clock;

class SystemClock implements Clock
{
    use BackwardSupport;

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
