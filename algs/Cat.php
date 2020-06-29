<?php
namespace Algs;

/**
 * p.51
 */

class Cat
{
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