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

    public function current()
    {
        if (null === $this->fixed) {
            return $this->innerClock->current();
        }

        return $this->fixed;
    }

    public function setCurrent(UTCDateTime $fixed)
    {
        $this->fixed = $fixed;
    }

    public function elapse(\DateInterval $amount)
    {
        $this->setCurrent($this->current()->add($amount));

        return $this->current();
    }

    public function reset()
    {
        $this->fixed = null;
    }
}
