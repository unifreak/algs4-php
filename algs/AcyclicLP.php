<?php
namespace Algs;
use Algs\EdgeWeightedTopological as Topological;

/**
 * p.428
 *
 * 无环加权有向图的最长路径算法
 *
 * 命题: p.428
 * - T: 解决无环加权有向图中的 **最长路径问题** 所需的时间与 E+V 成正比
 *   证明: 给定一个最长路径问题问题, 复制原始无环加权有向图得到一个副本并将副本中的所有边的权重
 *   取相反数. 这样, 副本中的最短路径即为原图中的最长路径. 要将最短路径问题的答案转换为最长路径
 *   的答案, 只需将方案中的权重变为正值即可. 根据命题 S 立即可以得到算法所需的时间
 *
 * 本实现使用另一种方法:
 * 修改 AcyclicSP, 将 distTo[] 的初始值变为负无穷, 并改变 relax() 方法中的不等式的方向
 */
class AcyclicLP extends SP
{
    public function __construct(EdgeWeightedDigraph $G, int $s)
    {
        parent::__construct($G, $s);

        for ($v = 0; $v < $G->V(); $v++) {
            $this->distTo[$v] = -INF; // 覆写为负无穷
        }
        $this->distTo[$s] = 0.0;

        $top = new Topological($G);
        foreach ($top->order() as $v) {
            $this->relax($G, $v);
        }
    }

    /**
     * 覆写父类
     */
    protected function relax(EdgeWeightedDigraph $G, int $v): void
    {
        foreach ($G->adj($v) as $e) {
            $w = $e->to();
            if ($this->distTo[$w] < $this->distTo[$v] + $e->weight()) { // 改变等式方向
                $this->distTo[$w] = $this->distTo[$v] + $e->weight();
                $this->edgeTo[$w] = $e;
            }
        }
    }
}