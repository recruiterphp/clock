<?php
namespace Recruiter\StopWatch;

class StopWatchNotStartedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("stopwatch hasn't been started yet");
    }
}
