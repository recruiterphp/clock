<?php
namespace Recruiter;

interface StopWatch
{
    public function start();

    /**
     * @return float
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedSeconds();

    /**
     * @return float
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMilliseconds();

    /**
     * @return float
     * @throws \Recruiter\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMicroseconds();
}
