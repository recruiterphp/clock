<?php
namespace RecruiterPhp;

use RecruiterPhp\DateTime\UTCDateTime;

interface UTCClock
{
    /**
     * @return UTCDateTime
     */
    public function current();
}
