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

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->current = call_user_func($this->incrementer, $this->current);
        $this->index++;
    }

    public function rewind()
    {
        $this->current = clone $this->from;
        $this->index = 0;
    }

    public function valid()
    {
        return call_user_func($this->comparator, $this->current, $this->to);
    }
}
