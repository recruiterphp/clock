<?php
namespace RecruiterPhp;

use DateTime;

interface Clock
{
    /**
     * @return DateTime
     */
    public function current();
}
