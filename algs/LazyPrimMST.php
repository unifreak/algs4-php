<?php
namespace Algs;

/**
 * p.400
 *
 * 生成 MST 的 Prim 算法的延时实现
 *
 * 命题: p.398
 * - M: Prim 算法的延时实现计算一幅含有 V 个顶点和 E 条边的连通加权无向图的最小生成树所需的空间与 E
 *   成正比, 所需的时间与 ElogE 成正比 (最坏情况)
 *   证明: 算法的瓶颈在于优先队列的 insert() 和 delMin() 方法中比较边的权重的次数. 优先队列中最多
 *   可能有 E 条边, 这就是空间需求的上限. 在最坏情况下, 一次插入的成本为 ~lgE, 删除最小元素的成本为
 *   ~2lgE (@see 命题 Q). 因为最多只能插入 E 条边, 删除 E 次最小元素, 时间上限显而易见
 *
 * 改进: 即时实现 @see PrimMST
 */
class LazyPrimMST extends MST
{
    private $marked; // MST 的顶点: 如果顶点 v 在树中, 则 marked[v] 为 true
    private $mst;    // MST 的边: 队列
    private $pq;     // 横切边 (包括失效的边): 优先队列 MinPQ 来根据权重比较所有边

    public function __construct(EdgeWeightedGraph $G)
    {
        $this->pq = new MinPQ(Edge::class, 10000); // @todo 硬编码的容量
        $this->marked = new Arr('bool', $G->V());
        $this->mst = new Queue();

        $this->visit($G, 0);
        while (! $this->pq->isEmpty()) { // 假设 G 是连通的
            $e = $this->pq->delMin();    // 从 pq 中得到权重最小的边
            $v = $e->either(); $w = $e->other($v);

            // 跳过失效的边
            // 连接新加入树中的顶点与其他已经在树中顶点的所有边都失效了,
            // 因为它的两个顶点都在树中, 这样的边都已经不是横切边了.
            // 这里使用延时实现, 即这些边仍留在优先队列中, 等到要删除它们的时候再检查边的有效性
            if ($this->marked[$v] && $this->marked[$w]) continue;

            $this->mst->enqueue($e);     // 将边添加到树中
            // 将未在 MST 中的顶点 (v 或 w) 添加到树中, 将新的横切边添加到优先队列中
            if (! $this->marked[$v]) $this->visit($G, $v);
            if (! $this->marked[$w]) $this->visit($G, $w);
        }
    }

    /**
     * 标记顶点 v 并将所有连接 v 和未被标记顶点的边 (横切边) 加入 pq
     */
    private function visit(EdgeWeightedGraph $G, int $v): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $e) {
            if (! $this->marked[$e->other($v)]) $this->pq->insert($e);
        }
    }

    public function edges(): \Iterator
    {
        return $this->mst;
    }
}