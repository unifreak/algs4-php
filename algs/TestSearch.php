<?php
namespace Algs;
use Algs\DepthFirstSearch as Search;

/**
 * p.338
 *
 * 图处理的用例: 找出给定顶点 s 所连通的所有顶点, 判断该图是否是连通图
 */
class TestSearch
{
    /**
     * % php TestSearch ../resource/tinyG.txt 0
     * 0 1 2 3 4 5 6
     * NOT connected
     *
     * % php TestSearch ../resource/tinyG.txt 9
     * 9 10 11 12
     * NOT connected
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        dump($G);
        $s = (int) $args[1];
        $search = new Search($G, $s);

        for ($v = 0; $v < $G->V(); $v++) {
            if ($search->marked($v))
                StdOut::print("$v ");
        }
        StdOut::println();

        if ($search->count() != $G->V())
            StdOut::print("NOT ");
        StdOut::println("connected");
    }
}