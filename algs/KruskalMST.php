<?php
namespace Algs;
use Algs\QuickUnionUF as UF;

/**
 * p.406
 *
 * 生成 MST 的 Kruskal 算法
 *
 * 命题: p.405
 * - N (续): 空间和 E 成正比, 所需的时间和 ElogE 成正比 (最坏情况)
 *   证明: 算法的实现在构造函数中使用所有边初始化优先队列, 成本最多为 E 次比较 (@see C2.4).
 *   优先队列构造完成后, 其余的部分和 Prim 算法完全相同. 优先队列中最多可能含有 E 条边, 即所
 *   需空间的上限. 每次操作的成本最多为 2lgE 次比较, 这就是时间上限的由来. Kruskal 算法最多
 *   还会进行 E 次 connected() 和 V 次 union() 操作, 但这些成本相比 ElogE 的总时间的增长
 *   数量级可以忽略不计
 *
 * Kruskal 算法一般还是比 Prim 算法慢, 因为在处理每条边时除了两种算法都要完成的优先队列操作之外,
 * 它还需要进行一次 connect() 操作
 */
class KruskalMST extends MST
{
    private $mst;

    public function __construct(EdgeWeightedGraph $G)
    {
        $this->mst = new Queue();
        $pq = new MinPQ(Edge::class, 10000);
        foreach ($G->edges() as $e) $pq->insert($e);
        $uf = new UF($G->V());   // 用于识别会形成环的边, 判断边是否无效

        while (! $pq->isEmpty() && $this->mst->size() < $G->V()-1) {
            $e = $pq->delMin();                     // 从 pq 得到权重最小的边和它的顶点
            $v = $e->either(); $w = $e->other($v);
            if ($uf->connected($v, $w)) continue;   // 忽略失效的边
            $uf->union($v, $w);                     // 合并分量
            $this->mst->enqueue($e);                // 将边添加到 MST 中
        }
    }

    public function edges(): \Iterator
    {
        return $this->mst;
    }
}
