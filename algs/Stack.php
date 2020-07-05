<?php

/**
 * p.94
 */

namespace Algs\Stack;

class Node
{
    public $item;
    public $next = null;
}


namespace Algs;
use Algs\Stack\Node as Node;

/**
 * 下压堆栈 (链表实现)
 */
class Stack implements \Iterator
{
    private $first;
    private $N = 0;

    // for Iterator
    private $pos = 0;
    private $current;

    public function isEmpty(): bool
    {
        return $this->first == null; // 或 $this->N == 0
    }

    public function size(): int
    {
        return $this->N;
    }

    public function push($item): void
    {
        $oldFirst = $this->first;
        $this->first = new Node();
        $this->first->item = $item;
        $this->first->next = $oldFirst;
        $this->N++;
    }

    public function pop()
    {
        $item = $this->first->item;
        $this->first = $this->first->next;
        $this->N--;
        return $item;
    }

    // Iterator implementation: LIFO
    // ===============================================================

    public function rewind() {
        $this->current = $this->first;
        $this->pos = $this->N;
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

    /**
     * % more tobe.txt
     * to be or not to - be - - that - - - is
     * % php ResizingArrayStack.php < tobe.txt
     * to be not that or be (2 left on stack)
     */
    public static function main(array $args): void
    {
        $s = new Stack();

        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if (is_null($item)) break;

            if ($item != '-') {
                $s->push($item);
            } elseif (! $s->isEmpty()) {
                StdOut::print("{$s->pop()} ");
            }
        }
        StdOut::println("( {$s->size()} left on stack)");

        foreach ($s as $i => $item) {
            StdOut::println("$i: $item");
        }
    }
}
