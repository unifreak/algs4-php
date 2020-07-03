<?php
namespace Algs;


/**
 * p.84, t.1.3.4
 */

/**
 * 通过构造函数传入的 $type 参数模拟 Java 泛型
 * 解决了 FixedCapacityStackOfString 类型约束的问题
 *
 * 定容问题和迭代问题 @see ResizingArrayStack
 */
class FixedCapacityStack
{
    private $a;
    private $N = 0;

    public function __construct($type, $cap)
    {
        $this->a = new Arr($type, $cap);
    }

    public function isEmpty()
    {
        return $this->N == 0;
    }

    public function size()
    {
        return $this->N;
    }

    public function push($item)
    {
        $this->a[$this->N++] = $item;
    }

    public function pop()
    {
        return $this->a[--$this->N];
    }

    public static function main()
    {
        $s = new FixedCapacityStack('string', 100);
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if ($item != '-') {
                $s->push($item);
            } elseif (! $s->isEmpty()) {
                StdOut::print("{$s->pop()} ");
            }
        }
        StdOut::println("( {$s->size()} left on stack)" );
    }
}