<?php
namespace Algs;

/**
 * p.438
 *
 * 基于队列的 Bellman-Ford 算法: 支持环, 负权重边
 *
 * 命题: p.436
 * - X (Bellman-Ford 算法): 在任意含有 V 个顶点的加权有向图中给定起点 s, 从 s 无法到达任何负权
 *   重环, 以下算法能够解决其中的单点最短路径问题: 将 distTo[s] 初始化为 0, 其他 distTo[] 元素
 *   初始化为无穷大. 以任意顺序放松有向图的所有边, 重复 V 轮 (这个方法非常通用, 因为它没有指定边的
 *   放松顺序)
 *   证明: 略
 * - X (续): Bellman-Ford 算法所需的时间和 EV 成正比, 空间和 V 成正比
 *   证明: 在每一轮中算法都会放松 E 条边, 重复 V 轮
 * - Y: 在最坏情况下基于队列的 Bellman-Ford 算法解决最短路径问题 (或者找到从 s 可达的负权重环)
 *   所需的时间与 EV 成正比, 空间和 V 成正比.
 *   证明: 如果不存在从 s 可达的负权重环, 算法会根据命题 X 在进行 V-1 轮放松操作后结束 (因为所有
 *   最短路径含有的边数都不大于 V-1). 如果的确存在一个从 s 可达的负权重环, 那么队列永远不可能为空.
 *   根据命题 X, 在第 V 轮放松之后, edgeTo[] 数组必然会包含一条含有一个环的路径 (从某个顶点 w 回
 *   到它自己) 且环的权重必然是负的. 因为 w 会在路径上出现两次且 s 到 w 的第二次出现处的路径长度
 *   小于 s 到 w 的第一次出现的路径长度. 在最坏情况下, 该算法的行为和通用算法相似并会将所有的 E 条
 *   边全部放松 V 轮
 */
class BellmanFordSP extends SP
{
    /**
     * 该顶点是否存在于队列中
     *
     * 一个由顶点索引的 bool 数组, 用来指示顶点是否已经存在于队列中, 以防止将顶点重复查入队列
     */
    private $onQ;
    /**
     * 正在被放松的顶点
     *
     * 根据经验很容易知道在任意一轮中许多边的放松都不会成功: 只有上一轮中 distTo[] 值发生变化
     * 的顶点指出的边才能够改变其他 distTo[] 元素的值. 为了记录这样的顶点, 我们使用了一条 FIFO 队列
     */
    private $queue;
    /**
     * relax() 的调用次数
     */
    private $cost = 0;
    /**
     * edgeTo[] 中是否有负权重环
     */
    private $cycle;

    public function __construct(EdgeWeightedDigraph $G, int $s)
    {
        parent::__construct($G, $s);

        $this->onQ = new Arr('bool', $G->V());
        $this->queue = new Queue();

        // 首先, 将其点 s 加入队列中, 然后进入一个循环
        $this->queue->enqueue($s);
        $this->onQ[$s] = true;

        // 然后进入一个循环, 其中每次都从队列中取出一个顶点并将其放松
        while (! $this->queue->isEmpty() && ! $this->hasNegativeCycle()) {
            $v = $this->queue->dequeue();
            $this->onQ[$v] = false;
            $this->relax($G, $v);
        }
    }

    /**
     * 覆盖父类: 将被成功放松的边所指向的顶点加入队列中
     */
    protected function relax(EdgeWeightedDigraph $G, int $v): void
    {
        foreach ($G->adj($v) as $e) {
            $w = $e->to();
            if ($this->distTo[$w] > $this->distTo[$v] + $e->weight()) {
                $this->distTo[$w] = $this->distTo[$v] + $e->weight();
                $this->edgeTo[$w] = $e;
                if (! $this->onQ[$w]) {
                    $this->queue->enqueue($w);
                    $this->onQ[$w] = true;
                }
            }
            // 每次调用 V 次 relax() 方法后, 检测是否存在负权重环
            if ($this->cost++ % $G->V() == 0) {
                $this->findNegativeCycle();
            }
        }
    }

    /**
     * 检测负权重环
     *
     * 命题 Y 说明在将所有边放松 V 轮之后当且仅当队列非空时有向图才存在从起点可达的负权重环.
     * 如果是这样, edgeTo[] 数组所表示的子图中必然含有这个负权重环
     */
    protected function findNegativeCycle(): void
    {
        $V = count($this->edgeTo);
        $spt = new EdgeWeightedDigraph($V);
        for ($v = 0; $v < $V; $v++) {
            if ($this->edgeTo[$v] !== null) {
                $spt->addEdge($this->edgeTo[$v]);
            }
        }

        $cf = new EdgeWeightedCycleFinder($spt);
        $this->cycle = $cf->cycle();
    }

    /**
     * 是否含有负权重环
     */
    public function hasNegativeCycle(): bool
    {
        return $this->cycle !== null;
    }

    /**
     * 负权重环
     */
    public function negativeCycle(): \Iterator
    {
        return $this->cycle;
    }
}