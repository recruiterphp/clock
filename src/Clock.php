<?php

declare(strict_types=1);

namespace Recruiter;

use Psr\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    /**
     * @deprecated Use now() instead
     */
    public function current(): \DateTime;
}
