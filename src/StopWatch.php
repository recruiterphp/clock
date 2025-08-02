<?php
namespace Recruiter;

interface StopWatch
{
    public function start();

    /**
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedSeconds(): float;

    /**
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMilliseconds(): float;

    /**
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMicroseconds(): float;
}
