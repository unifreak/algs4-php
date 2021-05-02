<?php
namespace Algs;

/**
 * p.95
 */
class Queue implements \Iterator
{
    private $first;
    private $last;
    private $N = 0;

    use IteratorTrait;

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
            // PHP 没有指针, 对象靠引用传递, 这里必须重新设置 $this->last 引用的对象
            // 否则会导致 $this->first 和 $this->last 始终引用的 $last 对象
            // $this->last->next 始终只能更改 $last 对象的 next 引用
            $this->last->next = $last;
            $this->last = $last;
        }
        $this->N++;
    }

    public function dequeue()
    {
        $item = $this->first->item;
        $this->first = $this->first->next;
        if ($this->isEmpty()) {
            $this->last = null; // 出队时, 注意维护队尾结点
        }
        $this->N--;
        return $item;
    }

    /**
     * % php ResizingArrayStack.php < ../data/tobe.txt
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