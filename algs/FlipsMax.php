<?php
namespace Algs;

/**
 * p.43
 */
class FlipsMax
{
    public static function max(Counter $x, Counter $y)
    {
        if ($x->tally() > $y->tally()) return $x;
        else                           return $y;
    }

    /**
     * % php FlipsMax.php 1000000
     * 500281 tails wins
     */
    public static function main($args)
    {
        $T = (int) $args[0];
        $heads = new Counter("heads");
        $tails = new Counter("tails");
        for ($t = 0; $t < $T; $t++)
            if (StdRandom::bernoulli(0.5))
                $heads->increment();
            else $tails->increment();

        if ($heads->tally() == $tails->tally())
            StdOut::println("Tie");
        else StdOut::println(self::max($heads, $tails) . " wins");
    }
}