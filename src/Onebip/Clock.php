<?php
namespace Onebip;

use DateTime;

interface Clock
{
    /**
     * @return DateTime
     */
    public function current();
}
