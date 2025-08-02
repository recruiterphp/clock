<?php

declare(strict_types=1);

namespace Recruiter;

interface StopWatch
{
    public function start();

    /**
     * @throws StopWatch\StopWatchNotStartedException
     */
    public function elapsedSeconds(): float;

    /**
     * @throws StopWatch\StopWatchNotStartedException
     */
    public function elapsedMilliseconds(): float;

    /**
     * @throws StopWatch\StopWatchNotStartedException
     */
    public function elapsedMicroseconds(): float;
}
