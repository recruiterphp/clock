<?php
namespace Recruiter;

use Recruiter\DateTime\UTCDateTime;

interface UTCClock
{
    /**
     * @return UTCDateTime
     */
    public function current();
}
