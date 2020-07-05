<?php
namespace Algs;

/**
 * p.57, t.1.2.13
 */

class Accumulator
{
    private $total = 0.0;
    private $N = 0;

    public function addDataValue(float $val): void
    {
        $this->N++;
        $this->total += $val;
    }

    public function mean(): float
    {
        return $this->total / $this->N;
    }

    public function __toString(): string
    {
        return
            "Mean ({$this->N} values: " . sprintf("%7.5f", $this->mean());
    }

    /**
     * % php Accumulator.php 1000
     * Mean (1000 values): 0.51829
     */
    public static function main(array $args): void
    {
        $T = (int) $args[0];
        $a = new Accumulator();
        for ($t = 0; $t < $T; $t++) {
            $a->addDataValue(StdRandom::random());
        }
        StdOut::println($a);
    }
}