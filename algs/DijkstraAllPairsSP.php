<?php
namespace Algs;

/**
 * p.424
 *
 * 任意顶点对之间的最短路径
 *
 * 所需时间和空间都与 EVlogV 成正比
 */
class DijkstraAllPairsSP
{
    private $all;

    public function __construct(EdgeWeightedDigraph $G)
    {
        $this->all = new Arr(DijkstraSP::class, $G->V());
        for ($v = 0; $v < $G->V(); $v++) {
            $this->all[$v] = new DijkstraSP($G, $v);
        }
    }

    public function path(int $s, int $t): \Iterator
    {
        return $this->all[$s]->pathTo($t);
    }

    public function dist(int $s, int $t): float
    {
        return $this->all[$s]->distTo($t);
    }
}