<?php
namespace Algs;

/**
 * p.110, t.1.4.1
 *
 * @todo  namespacing
 * Algs\Struct
 * Algs\Algs
 * Algs\Util
 * ...?
 */

class Stopwatch
{
    private $start;

    public function __construct()
    {
        $this->start = microtime(true);
    }

    public function elapsedTime()
    {
        $now = microtime(true);
        return sprintf("%.3f", $now - $this->start); // seconds
    }

    /**
     * % php Stopwatch.php 1000
     * 76 triples 7.230 seconds
     */
    public static function main(array $args): void
    {
        $N = (int) $args[0];
        $a = [];
        for ($i = 0; $i < $N; $i++) {
            $a[$i] = StdRandom::uniform(-1000000, 1000000);
        }
        $timer = new Stopwatch();
        $cnt = ThreeSum::count($a);
        StdOut::println("$cnt triples {$timer->elapsedTime()} seconds");
    }
}