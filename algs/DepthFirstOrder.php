<?php
namespace Algs;

/**
 * p.374
 *
 * 有向图中基于 DFS 的顶点排序
 *
 * 该类允许用例用各种顺序 (前, 后, 逆后) 遍历 DFS 经过的所有顶点. 这在高级的有向图处理算法中
 * 非常有用, 因为搜索的递归性使得我们能够证明这段计算的许多性质.
 */
class DepthFirstOrder
{
    private $marked;
    /**
     * 所有顶点的前序排列 (用队列存储)
     * 前序就是 dfs() 的调用顺序
     */
    private $pre;
    /**
     * 所有顶点的后序排列 (用队列存储)
     * 后序就是顶点遍历**完成**的顺序. 注意, 后序并非前序的逆序
     */
    private $post;
    /**
     * 所有顶点的逆后序排列 (用栈存储, 实现对 post 逆序)
     * 注意, 一幅有向无环图的拓扑排序即为所有顶点的逆后续排列 @see Topological
     */
    private $reversePost;   //

    public function __construct(Digraph $G)
    {
        $this->pre = new Queue();
        $this->post = new Queue();
        $this->reversePost = new Stack();
        $this->marked = new Arr('bool', $G->V());

        for ($v = 0; $v < $G->V(); $v++) {
            if (! $this->marked[$v]) {
                $this->dfs($G, $v);
            }
        }
    }

    private function dfs(Digraph $G, int $v): void
    {
        $this->pre->enqueue($v);
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w);
            }
        }
        $this->post->enqueue($v);
        $this->reversePost->push($v);
    }

    public function pre(): \Iterator
    {
        return $this->pre;
    }

    public function post(): \Iterator
    {
        return $this->post;
    }

    public function reversePost(): \Iterator
    {
        return $this->reversePost;
    }
}