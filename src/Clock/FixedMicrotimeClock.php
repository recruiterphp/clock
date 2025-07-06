<?php
namespace Recruiter\Clock;
use Recruiter\MicrotimeClock;

class FixedMicrotimeClock implements MicrotimeClock
{
    public function __construct(private $microseconds)
    {
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
