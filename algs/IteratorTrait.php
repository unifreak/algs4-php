<?php
namespace Algs;

trait IteratorTrait
{
    private $pos = 0;
    private $current;

    public function rewind() {
        $this->current = $this->first;
        $this->pos = $this->N - 1;
    }

    public function current()
    {
        return $this->current->item;
    }

    public function key()
    {
        return $this->pos;
    }

    public function next()
    {
        $this->current = $this->current->next;
        --$this->pos;
    }

    public function valid()
    {
        return ! is_null($this->current);
    }
}
