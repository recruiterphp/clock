<?php
namespace Recruiter\DateTime;

class RangeIterator implements \Iterator
{
    private $comparator;
    private $incrementer;
    private $index;

    private $current;

    public function __construct(private $from, private $to, callable $comparator, callable $incrementer)
    {
        $this->comparator = $comparator;
        $this->incrementer = $incrementer;

        $this->rewind();
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function key(): mixed
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->current = call_user_func($this->incrementer, $this->current);
        $this->index++;
    }

    public function rewind(): void
    {
        $this->current = clone $this->from;
        $this->index = 0;
    }

    public function valid(): bool
    {
        return call_user_func($this->comparator, $this->current, $this->to);
    }
}
