<?php
namespace Algs;

/**
 * p.51
 */
class Cat
{
    /**
     * % more in1.txt
     * This is
     *
     * % more in2.txt
     * a tiny
     * test.
     *
     * % php Cat.php in1.txt in2.txt out.txt
     * % more out.txt
     * This is
     * a tiny
     * test.
     */
    public static function main($args)
    {
        $out = new Out($args[count($args)-1]);
        for ($i = 0; $i < count($args)-1; $i++) {
            $in = new In($args[$i]);
            $s = $in->readAll();
            $out->println($s);
            $in->close();
        }
        $out->close();

    }
}