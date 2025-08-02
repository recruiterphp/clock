<?php
namespace Recruiter\Clock;
use Recruiter\MicrotimeClock;

class FixedMicrotimeClock implements MicrotimeClock
{
    public function __construct(private float $microseconds)
    {
    }

    public function current(): float
    {
        return $this->microseconds;
    }

    public function nowIs(float $microseconds): void
    {
        $this->microseconds = $microseconds;
    }
}
