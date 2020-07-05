<?php
namespace Algs;
use Algs\ResizingArrayStack as Stack;

/**
 * p.79
 */

class Reverse
{
    public static function main(array $args): void
    {
        $stack = new Stack('int', 10);
        while (! StdIn::isEmpty()) {
            if (is_null($i = StdIn::readInt())) break;
            $stack->push($i);
        }
        foreach ($stack as $i) {
            StdOut::println($i);
        }
    }
}