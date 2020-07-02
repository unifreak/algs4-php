<?php
namespace Algs;

class Stats
{
    public static function main($args)
    {
        $numbers = new Bag();

        while (! StdIn::isEmpty())
            $numbers->add(StdIn::readDouble());
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
