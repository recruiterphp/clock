<?php
namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

class SettableUTCClock implements UTCClock
{
    private $fixed;

    public function __construct(private readonly UTCClock $innerClock)
    {
    }

    public function current(): UTCDateTime
    {
        if (null === $this->fixed) {
            return $this->innerClock->current();
        }

        return $this->fixed;
    }

    public function setCurrent(UTCDateTime $fixed): void
    {
        $this->fixed = $fixed;
    }

    public function elapse(\DateInterval $amount): UTCDateTime
    {
        $this->setCurrent($this->current()->add($amount));

        return $this->current();
    }

    public function reset(): void
    {
        $this->fixed = null;
    }
}
