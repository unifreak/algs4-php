<?php
namespace Algs;

/**
 * p.403
 *
 * 生成 MST 的 Prim 算法即时版本
 *
 * 该算法向 MST 中添加的边的顺序和延时版本相同, 不同之处在于优先队列的操作.
 *
 * 因为我们感兴趣的只是连接树顶点和非树顶点中权重最小的边, 所以在优先队列中只需保存每个非树顶点
 * w 的_一条_边: 将它与树中的顶点连接起来的权重最小的那条边. 将 w 和树的顶点连接起来的其他权重
 * 较大的边迟早都会失效, 没必要保存它们
 *
 * 命题: p.404
 * - N: 所需的空间和 V 成正比, 时间和 ElogV 成正比 (最坏情况)
 *   证明: 因为优先队列中的顶点数最多为 V, 且使用了三条由顶点索引的数组, 所以所需空间的上限和 V
 *   成正比. 算法会进行 V 次 insert() 操作, V 次 delMin() 操作和 (在最坏情况下) E 次 change()
 *   操作. 已知在基于堆实现的索引优先队列中所有这些操作的增长数量级为 logV (@see 命题 Q), 所以
 *   将这些加起来可知算法所需时间和 ElogV 成正比
 *
 * 对于经常出现的巨型稀疏图, 和 LazyPrimMST 在时间上限上没有什么区别
 */
class PrimMST extends MST
{
    /**
     * 如果顶点 v 不在树中但至少含有一条边和树相连, 那么
     * - edgeTo[v] 是将 v 和树连接的最短边
     * - distTo[v] 为这条边的权重
     * - pq 为保存这类顶点的索引优先队列, 索引 v 关联的值是 edgeTo[v] 的边的权重
     * 这些性质的关键在于优先队列中的最小键即是权重最小的横切边的权重, 而和它相关联的顶点 v 就是
     * 下一个将被添加到树中的顶点. LazyPrimMST 中的判断条件 !marked[w] 等价于 distTo[w] 是无穷的
     */
    private $edgeTo; // 距离树最近的边
    private $distTo; // distTo[w]=edgeTo[w]->weight()
    private $marked; // 如果 v 在树中则为 true
    private $pq;     // 有效的横切边

    public function __construct(EdgeWeightedGraph $G)
    {
        $this->edgeTo = new Arr(Edge::class, $G->V());
        $this->distTo = new Arr('float', $G->V());
        $this->marked = new Arr('bool', $G->V());
        for ($v = 0; $v < $G->V(); $v++) {
            $this->distTo[$v] = INF;
        }
        $this->pq = new IndexMinPQ('float', $G->V());

        $this->distTo[0] = 0.0;
        $this->pq->insert(0, 0.0);
        while (! $this->pq->isEmpty()) {
            $this->visit($G, $this->pq->delMin());
        }
    }

    private function visit(EdgeWeightedGraph $G, int $v): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $e) {
            $w = $e->other($v);
            if ($this->marked[$w]) continue;
            if ($e->weight() < $this->distTo[$w]) {
                $this->edgeTo[$w] = $e;
                $this->distTo[$w] = $e->weight();
                if ($this->pq->contains($w)) $this->pq->change($w, $this->distTo[$w]);
                else                         $this->pq->insert($w, $this->distTo[$w]);
            }
        }
    }

    public function edges(): \Iterator
    {
        $mst = new Bag();
        for ($v = 1; $v < count($this->edgeTo); $v++) {
            $mst->add($this->edgeTo[$v]);
        }
        return $mst;
    }
}