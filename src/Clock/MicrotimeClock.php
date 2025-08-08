<?php

declare(strict_types=1);

namespace Recruiter\Clock;

interface MicrotimeClock
{
    /**
     * @see microtime()
     */
    public function now(): float;
}
