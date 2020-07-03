<?php
namespace Algs;

/**
 * p.84, t.1.3.4
 */

/**
 * 为了模拟 Java 数组, 这里使用了 Arr 对象而非原生 PHP 数组.
 * 对于书中的其他代码, 如果强调了 Java 数组的定容, 强制类型等特性的话, 都会用 Arr 对象模拟
 * Arr 对象定容, 只能数字作为索引, 且会检查值是否符合某种类型
 * 如 `new Arr('string', 10)` 数组只能存储 10 个项目, 且必须是 string 类型
 *
 * 这种实现有几个缺点:
 * 1. 只能处理字符串
 * 2. 定容, 用例必须预先估计栈的大小
 * 3. 不能使用 foreach 进行集合操作
 *
 * 以下是对应的解决方案
 * 1. 使用泛型 (这是java 特性, PHP 中的实现 @see FixedCapacityStackItem)
 * 2. 动态调整大小, @see ResizingArrayStack
 * 3. 实现迭代器, @see ResizingArrayStack
 */
class FixedCapacityStackOfStrings
{
    private $a;
    private $N = 0;
    public function __construct($cap)
    {
        $this->a = new Arr('string', (int) $cap);
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

    public static function main($args)
    {
        $s = new FixedCapacityStackOfStrings(100);
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if ($item != '-')
                $s->push($item);
            else if (! $s->isEmpty()) StdOut::print($s->pop() . " ");
        }
        StdOut::println("( {$s->size()} left on stack)");
    }
}