<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Recruiter\MicrotimeClock;

class SystemMicrotimeClock implements MicrotimeClock
{
    /**
     * @return float e.g. 1300000000.234567
     */
    public function current(): float
    {
        return microtime(true);
    }
}
