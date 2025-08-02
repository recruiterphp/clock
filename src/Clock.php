<?php

declare(strict_types=1);

namespace Recruiter;

interface Clock
{
    public function current(): \DateTime;
}
