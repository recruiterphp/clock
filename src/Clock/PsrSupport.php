<?php

namespace Recruiter\Clock;

trait PsrSupport
{
    abstract function current(): \DateTime;

    public function now(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromMutable($this->current());
    }
}