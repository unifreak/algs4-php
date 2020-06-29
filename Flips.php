<?php
namespace Algs;

/**
 * p.42
 */

class Flips
{
    public static function main($args)
    {
        $T = (int) $args[0];
        $heads = new Counter("heads");
        $tails = new Counter("tails");
        for ($t = 0; $t < $T; $t++) {
            if (StdRandom::bernoulli(0.5))
                $heads->increment();
            else $tails->increment();
        }
        StdOut::println($heads);
        StdOut::println($tails);
        $d = $heads->tally() - $tails->tally();
        StdOut::println("delta: " . abs($d));
    }
}
