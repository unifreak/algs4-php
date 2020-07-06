<?php
namespace Algs;

/**
 * p.77
 */

class Stats
{
    /**
     * % php Stats.php
     # 100
     # 99
     # 101
     # 120
     # 98
     # 107
     # 109
     # 81
     # 101
     # 90
     # Mean: 100.60
     # Std dev: 10.51
     */
    public static function main($args)
    {
        $numbers = new Bag();

        while (! StdIn::isEmpty()) {
            $n = StdIn::readDouble();
            if (is_null($n)) {
                break;
            }
            $numbers->add($n);
        }
        $N = $numbers->size();

        $sum = 0.0;
        foreach ($numbers as $x)
            $sum += $x;
        $mean = $sum/$N;

        $sum = 0.0;
        foreach ($numbers as $x)
            $sum += ($x - $mean)*($x - $mean);
        $std = sqrt($sum/($N-1));

        StdOut::printf("Mean: %.2f\n", $mean);
        StdOut::printf("Std dev: %.2f\n", $std);
    }
}
