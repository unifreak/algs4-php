<?php
namespace Algs\Queue;

class Node
{
    public $item = null;
    public $next = null;
}

/**
 * p.95
 */

namespace Algs;
use Algs\Queue\Node as Node;

class Queue implements \Iterator
{
    private $first;
    private $last;
    private $N = 0;

    // for Iterator
    private $pos = 0;
    private $current;

    public function isEmpty(): bool
    {
        return is_null($this->first);
    }

    public function size(): int
    {
        return $this->N;
    }

    public function enqueue($item): void
    {
        $last = new Node();
        $last->item = $item;
        $last->next = null;
        if ($this->isEmpty()) {
            $this->first = $last; // 入队时, 注意维护队头结点
            // fix warning: Creating default object from empty value
            $this->last = $last;
        } else {
            $this->last->next = $last;
            // PHP 没有指针, 对象靠引用传递, 这里必须重新设置 $this->last 引用的对象
            $this->last = $last;
        }
        $this->N++;
    }

    public function dequeue()
    {
        $item = $this->first->item;
        $this->first = &$this->first->next;
        if ($this->isEmpty()) {
            $this->last = null; // 出队时, 注意维护队尾结点
        }
        $this->N--;
        return $item;
    }

    // Iterator implementation: FIFO
    // @todo: Trait for Stack/Queue/Bag Iterator?
    // ===============================================================

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

    /**
     * % more tobe.txt
     * to be or not to - be - - that - - - is
     * % php ResizingArrayStack.php < tobe.txt
     * to be not that or be (2 left on stack)
     */
    public static function main(array $args): void
    {
        $q = new Queue();
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if (is_null($item)) {
                break;
            }

            if ($item != '-') {
                $q->enqueue($item);
            } elseif (! $q->isEmpty()) {
                StdOut::print("{$q->dequeue()} ");
            }
        }
        StdOut::println("({$q->size()} left on queue)");

        foreach ($q as $i => $item) {
            StdOut::println("$i: $item");
        }
    }
}