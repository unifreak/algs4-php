<?php
namespace Algs;

/**
 * p.357, p.358
 *
 * 这段代码使用了 SymbolGraph 和 BreadthFirstPaths 来查找图中的最短路径
 */
class DegreesOfSeparation
{
    /**
     * % php DegreesOfSeparation.php ../resource/movies.txt "/" "Bacon, Kevin"
     * Grant, Cary
     * Bacon, Kevin
     * Footloose (1984)
     * Parker, Sarah Jessica
     * Ed Wood (1994)
     * LeBell, Gene
     * Dead Men Don't Wear Plaid (1982)
     * Grant, Cary
     */
    public static function main(array $args): void
    {
        $sg = new SymbolGraph($args[0], $args[1]);
        $G = $sg->G();
        $source = $args[2];
        if (! $sg->contains($source)) {
            StdOut::println("$source not in database.");
            return;
        }

        $s = $sg->index($source);
        $bfs = new BreadthFirstPaths($G, $s);
        while (! StdIn::isEmpty()) {
            $sink = StdIn::readLine();
            if ($sg->contains($sink)) {
                $t = $sg->index($sink);
                if ($bfs->hasPathTo($t)) {
                    foreach ($bfs->pathTo($t) as $v) {
                        StdOut::println("  {$sg->name($v)}");
                    }
                } else {
                    StdOut::println("Not connected");
                }
            } else {
                StdOut::println("Not in database.");
            }
        }
    }
}