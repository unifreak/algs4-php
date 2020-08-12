<?php
namespace Algs;

/**
 * p.98
 */
class Bag implements \Iterator
{
    private $first = null;
    private $N = 0; // to use IteratorTrait, there should be N

    use IteratorTrait;

    public function add($item): void
    {
        $oldFirst = $this->first;
        $this->first = new Node();
        $this->first->item = $item;
        $this->first->next = $oldFirst;
        $this->N++;
    }

    public function size(): int
    {
        return $this->N;
    }
}
