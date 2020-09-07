<?php
namespace Algs;

/**
 * p.446, e.4.4.12
 *
 * 加权有向图的拓扑排序
 * 同 @see Topolocial, 修改其为使用 EdgeWeightedCycleFinder 和 EdgeWeightedDigraph 两个类
 */
class EdgeWeightedTopological
{
    private $order;  // 顶点的拓扑排序

    public function __construct(EdgeWeightedDigraph $G)
    {
        $cycleFinder = new EdgeWeightedCycleFinder($G);
        if (! $cycleFinder->hasCycle()) {
            $dfs = new EdgeWeightedDepthFirstOrder($G);
            $this->order = $dfs->reversePost();
        }
    }

    /**
     * 拓扑排序的所有顶点
     */
    public function order(): \Iterator
    {
        return $this->order;
    }

    /**
     * G 是有向无环图吗
     */
    public function isDAG(): bool
    {
        return $this->order !== null;
    }
}