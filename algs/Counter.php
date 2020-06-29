<?php
namespace Algs;

use SGH\Comparable\Comparable;

/**
 * p.39, t.1.2.1
 */

class Counter implements Comparable
{
    private $name;
    private $count = 0;

    public function __construct($id)
    {
        $this->name = $id;
    }

    public function increment()
    {
        $this->count++;
    }

    public function tally()
    {
        return $this->count;
    }

    public function __toString()
    {
        return $this->count . " " . $this->name;
    }

    public function compareTo($that)
    {
        if (! $that instanceof static) {
            throw new \LogicException(
                'You cannot compare sheep with the goat.'
            );
        }

        if ($this->count < $that->count) return -1;
        elseif ($this->count > $that->count) return 1;
        else return 0;
    }

    public static function main($args)
    {
        $n = (int) $args[0];
        $trials = (int) $args[1];

        // create n counters
        $hits = [];
        for ($i = 0; $i < $n; $i++) {
            $hits[] = new Counter("counter" . $i);
        }

        // increment trials counters at random
        for ($t = 0; $t < $trials; $t++) {
            $hits[StdRandom::uniform($n)]->increment();
        }

        // print results
        for ($i = 0; $i < $n; $i++) {
            StdOut::println($hits[$i]);
        }
    }
}
