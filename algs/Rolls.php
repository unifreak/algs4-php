<?php
namespace Algs;

/**
 * p.44
 */
class Rolls
{
    /**
     * % php Rolls.php 10000000
     * 167308 1's
     * 166540 2's
     * 166087 3's
     * 167051 4's
     * 166422 5's
     * 166592 6's
     */
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