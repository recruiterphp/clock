<?php

declare(strict_types=1);

namespace Recruiter;

interface StopWatch
{
    public function start(): void;

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
