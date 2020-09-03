<?php
namespace Algs;

/**
 * p.385
 *
 * 用传递闭包解决顶点对的可达性问题
 *
 * 构造函数所需的空间和 V^2 成正比, 时间和 V(V+E) 成正比. 所以它不适合在实际应用中遇到的大型有向图.
 * 如何大幅减少预处理所需的时间和空间同时又保证常数时间的查询仍是有待解决的问题, 并且有重要的实际
 * 意义: 除非解决了这个问题, 对于像代表互联网这样的巨型有向图, 是无法有效解决其中的顶点对可达性问
 * 题的.
 *
 */
class TransitiveClosure
{
    private $all;

    public function __construct(Digraph $G)
    {
        $this->all = new Arr(DirectedDFS::class, $G->V());
        for ($v = 0; $v < $G->V(); $v++) {
            $this->all[$v] = new DirectedDFS($G, $v);
        }
    }

    /**
     * w 是从 v 可达的吗
     */
    public function reachable(int $v, int $w): bool
    {
        return $this->all[$v]->marked($w);
    }
}