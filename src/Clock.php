<?php
namespace Recruiter;

use DateTime;

interface Clock
{
    public function current(): DateTime;
}
