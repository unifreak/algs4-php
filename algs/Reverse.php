<?php
namespace Algs;

/**
 * p.79
 */

class Reverse
{
    public static function main(array $args): void
    {
        $stack = new Stack();
        while (! StdIn::isEmpty()) {
            if (is_null($i = StdIn::readInt())) break;
            $stack->push($i);
        }
        foreach ($stack as $i) {
            StdOut::println($i);
        }
    }
}