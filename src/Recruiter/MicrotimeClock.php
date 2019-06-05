<?php
namespace Recruiter;

interface MicrotimeClock
{
    /**
     * @see microtime()
     * @return float
     */
    public function current();
}
