<?php
namespace RecruiterPhp;

interface MicrotimeClock
{
    /**
     * @see microtime()
     * @return float
     */
    public function current();
}
