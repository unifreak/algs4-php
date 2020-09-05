<?php
namespace Algs;

/**
 * p.397
 */
class MST
{
    /**
     * MST 的所有边
     */
    public function edges(): \Iterator { }

    /**
     * MST 的权重
     */
    public function weight(): float
    {
        $weight = 0.0;
        foreach ($this->edges() as $e) {
            $weight += $e->weight();
        }
        return $weight;
    }

    /**
     * php LazyPrimMST.php ../resource/tinyEWG.txt
     * 0-7 0.16
     * 1-7 0.19
     * 0-2 0.26
     * 2-3 0.17
     * 5-7 0.28
     * 4-5 0.35
     * 6-2 0.40
     * 1.81
     *
     * php LazyPrimMST.php ../resource/mediumEWG.txt
     * 0-225 0.02
     * 49-225 0.03
     * 44-49 0.02
     * 44-204 0.02
     * ...
     * 31-241 0.08
     * 89-127 0.11
     * 10.46351
     *
     * php PrimMST.php ../resource/tinyEWG.txt
     * 0-7 0.16
     * 6-2 0.40
     * 5-7 0.28
     * 4-5 0.35
     * 2-3 0.17
     * 0-2 0.26
     * 1-7 0.19
     * 1.81
     *
     * php PrimMST.php ../resource/mediumEWG.txt
     * 0-225 0.02
     * 49-225 0.03
     * 44-49 0.02
     * 44-204 0.02
     * ...
     * 31-241 0.08
     * 89-127 0.11
     * 10.46351
     *
     * php KruskalMST.php ../resource/tinyEWG.txt
     * 0-7 0.16
     * 2-3 0.17
     * 1-7 0.19
     * 0-2 0.26
     * 5-7 0.28
     * 4-5 0.35
     * 6-2 0.40
     * 1.81
     *
     * php PrimMST.php ../resource/mediumEWG.txt
     * 0-225 0.02
     * 49-225 0.03
     * 44-49 0.02
     * 44-204 0.02
     * ...
     * 31-241 0.08
     * 89-127 0.11
     * 10.46351
     */
    public static function main(array $args): void
    {
        $in = new In($args[0]);
        $G = new EdgeWeightedGraph($in);

        $mst = new static($G);
        foreach ($mst->edges() as $e) {
            StdOut::println($e);
        }
        StdOut::println($mst->weight());
    }
}