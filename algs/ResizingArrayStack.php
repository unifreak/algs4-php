<?php
namespace Algs;

/**
 * p.88
 */

/**
 * resize() 解决了定容问题
 * 通过实现 \Iterator 接口可迭代
 *
 * 这个算法十分重要, 因为它几乎达到了任意集合类数据类型的实现的最佳性能
 * - 每项操作用时都与集合大小无关
 * - 空间需求总是不超过集合大小乘以一个常数
 */
class ResizingArrayStack implements \Iterator
{
    private $a;
    private $N = 0;
    private $pos = 0; // Iterator 索引指针
    private $type;

    public function __construct($type, $cap)
    {
        $this->a = new Arr($type, $cap);
        $this->type = $type;
    }

    public function isEmpty()
    {
        return $this->N == 0;
    }

    public function size()
    {
        return $this->N;
    }

    /**
     * 这里并没有使用 SplFixedArray 内置的 setSize() 方法
     */
    private function resize(int $max)
    {
        $temp = new Arr($this->type, $max);
        for ($i = 0; $i < $this->N; $i++) {
            $temp[$i] = $this->a[$i];
        }
        $this->a = $temp;
    }

    public function push($item)
    {
        $length = $this->a->count();
        if ($this->N == $length) {
            $this->resize(2 * $length);
        }
        $this->a[$this->N++] = $item;
    }

    public function pop()
    {
        $item = $this->a[--$this->N];
        if ($this->N > 0 && $this->N == count($this->a) / 4) {
            $this->resize(count($this->a) / 2);
        }
        return $item;
    }

    // Iterator implementation: LIFO
    // ===============================================================

    public function rewind() {
        $this->pos = $this->N - 1;
    } // do nothing

    public function current()
    {
        return $this->a[$this->pos];
    }

    public function key()
    {
        return $this->pos;
    }

    public function next()
    {
        --$this->pos;
    }

    public function valid()
    {
        return isset($this->a[$this->pos]);
    }

    public static function main($args)
    {
        $s = new ResizingArrayStack('string', 100);
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if ($item != '-')
                $s->push($item);
            else if (! $s->isEmpty()) StdOut::print($s->pop() . " ");
        }
        StdOut::println("( {$s->size()} left on stack)");

        foreach ($s as $i => $item) {
            StdOut::println("$i: $item");
        }
    }
}