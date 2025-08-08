<?php

declare(strict_types=1);

namespace Recruiter;

use Symfony\Component\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    /**
     * @deprecated Use now() instead
     */
    public function current(): \DateTime;
}
