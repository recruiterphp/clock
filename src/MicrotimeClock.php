<?php

declare(strict_types=1);

namespace Recruiter;

interface MicrotimeClock
{
    /**
     * @see microtime()
     */
    public function current(): float;
}
