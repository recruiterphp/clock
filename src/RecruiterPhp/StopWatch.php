<?php
namespace RecruiterPhp;

interface StopWatch
{
    public function start();

    /**
     * @return float
     * @throws \RecruiterPhp\StopWatch\StopWatchNotStartedException
     */
    public function elapsedSeconds();

    /**
     * @return float
     * @throws \RecruiterPhp\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMilliseconds();

    /**
     * @return float
     * @throws \RecruiterPhp\StopWatch\StopWatchNotStartedException
     */
    public function elapsedMicroseconds();
}
