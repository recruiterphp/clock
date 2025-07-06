<?php
namespace Recruiter;

interface MicrotimeClock
{
    /**
     * @see microtime()
     */
    public function current(): float;
}
