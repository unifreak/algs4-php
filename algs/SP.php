<?php
namespace Algs;

/**
 * t.4.4.4, p.417
 */
class SP
{
    protected $edgeTo; // 从起点到某个顶点的最后一条边
    protected $distTo; // 从起点到某个顶点的路径长度

    public function __construct(EdgeWeightedDigraph $G, int $s)
    {
        $this->edgeTo = new Arr(DirectedEdge::class, $G->V());
        $this->distTo = new Arr('float', $G->V());
        for ($v = 0; $v < $G->V(); $v++) {
            $this->distTo[$v] = INF;
        }
        $this->distTo[$s] = 0.0;
    }

    /**
     * 顶点的松弛
     */
    protected function relax(EdgeWeightedDigraph $G, int $v): void
    {
        foreach ($G->adj($v) as $e) {
            $w = $e->to();
            if ($this->distTo[$w] > $this->distTo[$v] + $e->weight()) {
                $this->distTo[$w] = $this->distTo[$v] + $e->weight();
                $this->edgeTo[$w] = $e;
            }
        }
    }

    /**
     * 从顶点 s 到 v 的距离, 如果不存在则路径为无穷大
     */
    public function distTo(int $v): float
    {
        return $this->distTo[$v];
    }

    /**
     * 是否存在从顶点 s 到 v 的路径
     */
    public function hasPathTo(int $v): bool
    {
        return $this->distTo[$v] < INF;
    }

    /**
     * 从顶点 s 到 v 的路径, 如果不存在则为 null
     */
    public function pathTo(int $v): \Iterator
    {
        if (! $this->hasPathTo($v)) return null;
        $path = new Stack();
        for ($e = $this->edgeTo[$v]; $e !== null; $e = $this->edgeTo[$e->from()]) {
            $path->push($e);
        }
        return $path;
    }

    /**
     * php DijkstraSP.php ../data/tinyEWD.txt 0
     * 0 to 0 (0.00):
     * 0 to 1 (1.05): 0->4 0.38    4->5 0.35    5->1 0.32
     * 0 to 2 (0.26): 0->2 0.26
     * 0 to 3 (0.99): 0->2 0.26    2->7 0.34    7->3 0.39
     * 0 to 4 (0.38): 0->4 0.38
     * 0 to 5 (0.73): 0->4 0.38    4->5 0.35
     * 0 to 6 (1.51): 0->2 0.26    2->7 0.34    7->3 0.39    3->6 0.52
     * 0 to 7 (0.60): 0->2 0.26    2->7 0.34
     *
     * % php AcyclicSP.php ../data/tinyEWDAG.txt 5
     * 5 to 0 (0.73): 5->4 0.35    4->0 0.38
     * 5 to 1 (0.32): 5->1 0.32
     * 5 to 2 (0.62): 5->7 0.28    7->2 0.34
     * 5 to 3 (0.61): 5->1 0.32    1->3 0.29
     * 5 to 4 (0.35): 5->4 0.35
     * 5 to 5 (0.00):
     * 5 to 6 (1.13): 5->1 0.32    1->3 0.29    3->6 0.52
     * 5 to 7 (0.28): 5->7 0.28
     *
     * % php AcyclicLP.php ../data/tinyEWDAG.txt 5
     * 5 to 0 (2.44): 5->1 0.32    1->3 0.29    3->6 0.52    6->4 0.93    4->0 0.38
     * 5 to 1 (0.32): 5->1 0.32
     * 5 to 2 (2.77): 5->1 0.32    1->3 0.29    3->6 0.52    6->4 0.93    4->7 0.37    7->2 0.34
     * 5 to 3 (0.61): 5->1 0.32    1->3 0.29
     * 5 to 4 (2.06): 5->1 0.32    1->3 0.29    3->6 0.52    6->4 0.93
     * 5 to 5 (0.00):
     * 5 to 6 (1.13): 5->1 0.32    1->3 0.29    3->6 0.52
     * 5 to 7 (2.43): 5->1 0.32    1->3 0.29    3->6 0.52    6->4 0.93    4->7 0.37
     *
     * php BellmanFordSP.php ../data/tinyEWDn.txt 0
     * 0 to 0 (0.00):
     * 0 to 1 (0.93): 0->2 0.26    2->7 0.34    7->3 0.39    3->6 0.52    6->4 -1.25
     *                4->5 0.35    5->1 0.32
     * 0 to 2 (0.26): 0->2 0.26
     * 0 to 3 (0.99): 0->2 0.26    2->7 0.34    7->3 0.39
     * 0 to 4 (0.26): 0->2 0.26    2->7 0.34    7->3 0.39    3->6 0.52    6->4 -1.25
     * 0 to 5 (0.61): 0->2 0.26    2->7 0.34    7->3 0.39    3->6 0.52    6->4 -1.25
     *                4->5 0.35
     * 0 to 6 (1.51): 0->2 0.26    2->7 0.34    7->3 0.39    3->6 0.52
     * 0 to 7 (0.60): 0->2 0.26    2->7 0.34
     *
     * @todo 用例不通过
     * % php BellmanFordSP ../data/tinyEWDnc.txt 0
     *  4->5  0.35
     *  5->4 -0.66
     */
    public static function main(array $args): void
    {
        $G = new EdgeWeightedDigraph(new In($args[0]));
        $s = (int) $args[1];
        $sp = new static($G, $s);

        for ($t = 0; $t < $G->V(); $t++) {
            StdOut::print("$s to $t");
            StdOut::printf(" (%4.2f): ", $sp->distTo($t));
            if ($sp->hasPathTo($t)) {
                foreach ($sp->pathTo($t) as $e) {
                    StdOut::print("$e    ");
                }
            }
            StdOut::println();
        }
    }
}