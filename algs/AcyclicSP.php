<?php
namespace Algs;
use Algs\EdgeWeightedTopological as Topological;

/**
 * p.427
 *
 * 无环加权有向图的最短路径算法
 *
 * 算法: 是无环有向图的拓扑排序算法的简单扩展
 * 1. 将 distTo[s] 初始化为 0, 其他 distTo[] 元素初始化为无穷大
 * 2. 一个一个地按照拓扑顺序放松所有顶点
 *
 * 特点:
 * - 在线性时间内解决单点最短路径问题, 比 Dijkstra 算法更快, 更简单
 * - 能够处理负权重的边
 * - 能够解决相关的问题, 比如找出最长的路径
 *
 * 命题: p.426
 * - S: 按照拓扑顺序放松顶点, 就能在和 E+V 成正比的时间内解决无环加权有向图的单点最短路径问题
 *   证明:
 *   + 正确性: 每条边 v->w 都只会被放松一次. 当 v 被放松时, 得到: distTo[w]<=distTo[v]+e->weight().
 *     在算法结束前该不等式都成立, 因为 distTo[v] 是不会变化的 (因为是按照拓扑顺序放松顶点, 在 v
 *     被放松之后算法不会再处理任何指向 v 的边) 而 distTo[w] 只会变小 (任何放松操作都只会减小 distTo[]
 *     中的元素的值). 因此, 在所有从 s 可达的顶点都被加入到树中后, 最短路径的最优性条件成立, 命题 Q
 *     也就成立了
 *   + 时间上限: 命题 G 告诉我们拓扑排序所需的时间与 E+V 成正比, 而在第二次遍历中每条边都只会被放松
 *     一次, 因此算法总耗时与 E+V 成正比
 * 在已知加权图是无环的情况下, 它是找出最短路径的最好方法
 */
class AcyclicSP extends SP
{
    public function __construct(EdgeWeightedDigraph $G, int $s)
    {
        parent::__construct($G, $s);

        $top = new Topological($G);
        foreach ($top->order() as $v) {
            $this->relax($G, $v);
        }
    }
}