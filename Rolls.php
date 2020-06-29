<?php
namespace Algs;

/**
 * p.44
 */

class Rolls
{
    public static function main($args)
    {
        $T = (int) $args[0];
        $SIDES = 6;
        $rolls = [];
        for ($i = 1; $i <= $SIDES; $i++)
            $rolls[$i] = new Counter($i . "'s");
        for ($t = 0; $t < $T; $t++) {
            $result = StdRandom::uniform(1, $SIDES+1);
            $rolls[$result]->increment();
        }
        for ($i = 1; $i <= $SIDES; $i++)
            StdOut::println($rolls[$i]);
    }
}