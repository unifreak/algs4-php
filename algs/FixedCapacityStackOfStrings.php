<?php
namespace Algs;

/**
 * p.84, t.1.3.4
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
            dump("item:$item");
            if (! $item == '-')
                $s->push($item);
            else if (! $s->isEmpty()) StdOut::print($s->pop() . " ");
        }
        var_dump($s);
        StdOut::println("( {$s->size()} left on stack)");
    }
}