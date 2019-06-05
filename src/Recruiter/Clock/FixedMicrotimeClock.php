<?php
namespace Recruiter\Clock;
use Recruiter\MicrotimeClock;

class FixedMicrotimeClock implements MicrotimeClock
{
    private $microseconds;

    public function __construct($microseconds)
    {
        $this->microseconds = $microseconds;
    }

    /**
     * @return float
     */
    public function current()
    {
        return $this->microseconds;
    }

    public function nowIs($microseconds)
    {
        $this->microseconds = $microseconds;
    }
}
