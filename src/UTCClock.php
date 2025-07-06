<?php
namespace Recruiter;

use Recruiter\DateTime\UTCDateTime;

interface UTCClock
{
    public function current(): UTCDateTime;
}
