<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

interface StopWatch
{
    public function start(): void;

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedSeconds(): float;

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedMilliseconds(): float;

    /**
     * @throws StopWatchNotStartedException
     */
    public function elapsedMicroseconds(): float;
}
