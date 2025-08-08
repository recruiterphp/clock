<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

class StopWatchNotStartedException extends \LogicException
{
    public function __construct()
    {
        parent::__construct("stopwatch hasn't been started yet");
    }
}
