<?php
namespace Recruiter;

use DateTime;

interface Clock
{
    /**
     * @return DateTime
     */
    public function current();
}
