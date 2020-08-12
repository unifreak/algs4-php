<?php
namespace Algs;

/**
 * p.22, t.1.1.14
 */
final class StdOut
{
    private function __construct() { }

    public static function println($s="")
    {
        echo $s . PHP_EOL;
    }

    public static function print($s)
    {
        echo $s;
    }

    public static function printf($f, ...$s)
    {
        printf($f, ...$s);
    }

    public static function main($args)
    {
        StdOut::println("Test");
        StdOut::println(17);
        StdOut::println(true);
        StdOut::printf("%.6f\n", 1.0/7.0);
    }
}