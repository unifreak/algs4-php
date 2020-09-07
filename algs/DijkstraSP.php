<?php
namespace Algs;

/**
 * p.423
 *
 * 最短路径的 Dijkstra 算法: 支持有环, 不支持负权重值
 *
 * V.S @see Prim 算法
 * - 两种算法都会用添加边的方式构造一棵树
 *   + Prim 算法每次添加的都是离 _树_ 最近的非树顶点
 *   + Dijkstra 算法每次添加的都是离 _起点_ 最近的非树顶点
 * - 都不需要 marked[] 数组, 因为条件 !marked[w] 等价于条件 distTo[w] 为无穷大
 * - 将本实现中的有向图换成无向图, 并忽略 relax() 方法中的 distTo[v] 部分的代码, 即可得到 Prim 算法
 *
 * Dijkstra 算法:
 * 1. 首先将 distTo[s] 初始化为 0, distTo[] 中的其他元素初始化为正无穷.
 * 2. 然后将 distTo[] 最小的非树顶点放松并加入树中
 * 3. 如此这般, 直到所有的顶点都在树中或者所有的非树顶点的 distTo[] 值均为无穷大
 *
 * 命题: p.421
 * - R: Dijkstra 算法能够解决边 _权重非负_ 的加权有向图的单起点最短路径问题
 *   证明: 如果 v 是从起点可达的, 那么所有 v->w 的边都只会被放松一次. 当 v 被放松时, 必有
 *   distTo[w] <= distTo[v] + e->weight(). 该不等式在算法结束前都会成立, 因此 distTo[w]
 *   只会变小 (放松操作只会减小 distTo[] 的值) 而 distTo[v] 则不会改变 (因为边的权重非负且在
 *   每一步中算法都会选择 distTo[] 最小的顶点, 之后的放松操作不可能使任何 distTo[] 的值小于
 *   distTo[v]). 因此, 在所有 s 可达的顶点均被添加到树中之后, 最短路径的最优性条件成立, 即命题
 *   P 成立.
 * - R (续): 使用 Dijkstra 算法计算根结点为给定起点的最短路径树所需的空间与 V 成正比, 时间与
 *   ElogV 成正比 (最坏情况下)
 *   证明: 同 Prim 算法的证明 (@see 命题 N)
 *
 * 问题变种:
 * - 无向图中的单点最短路径问题:
 *   如果将无向图看成有向图, 且对于无向图中的每条边, 创建两条方向不同且权重相同的有向边, 则这个
 *   算法也适用 -- 最短路径的问题是等价的
 * - 给定两点的最短路径: 给定起点 s 和终点 t, 找到从 s 到 t 的最短路径
 *   使用本算法, 从优先队列中中取到 t 之后终止搜索即可
 * - 任意顶点对之间的最短路径:
 *   @see  DijkstraAllPairsSP
 * - 欧几里得图中的最短路径
 */
class DijkstraSP extends SP
{
    private $pq; // 索引优先队列, 保存需要被放松的顶点并确认下一个被放松的顶点

    public function __construct(EdgeWeightedDigraph $G, int $s)
    {
        parent::__construct($G, $s);

        $this->pq = new IndexMinPQ('float', $G->V());
        $this->pq->insert($s, 0.0);
        while (! $this->pq->isEmpty()) {
            $this->relax($G, $this->pq->delMin());
        }
    }

    /**
     * 覆写父类: 加入对 pq 的维护代码
     */
    protected function relax(EdgeWeightedDigraph $G, int $v): void
    {
        foreach ($G->adj($v) as $e) {
            $w = $e->to();
            if ($this->distTo[$w] > $this->distTo[$v] + $e->weight()) {
                $this->distTo[$w] = $this->distTo[$v] + $e->weight();
                $this->edgeTo[$w] = $e;
                if ($this->pq->contains($w)) $this->pq->change($w, $this->distTo[$w]);
                else $this->pq->insert($w, $this->distTo[$w]);
            }
        }
    }
}