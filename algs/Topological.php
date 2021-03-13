<?php
namespace Algs;

/**
 * p.375, t.4.2.6
 *
 * 使用 DFS 进行拓扑排序
 *
 * 命题: p.373, p.376, p.377
 * - E: 当且仅当一幅有向图是无环图时它才能进行拓扑排序
 * - F: 一幅有向无环图的拓扑顺序即为所有顶点的逆后续排序
 *   证明: 对于任意边 v->w, 在调用 dfs(v) 时, 下面三种情况必有其一成立:
 *   + dfs(w) 已经被调用过且已经返回了 (w 已经被标记)
 *   + dfs(w) 还没有被调用 (w 还未被标记), 因此 v->w 会直接或间接调用并返回 dfs(w), 且 dfs(w)
 *     会在 dfs(v) 返回前返回
 *   + dfs(w) 已经被调用但还未返回. 证明的关键在于, 在有向无环图中这种情况是不可能出现的. 这是
 *     由于递归调用链意味着存在着从 w 到 v 的路径, 但存在 v->w 则表示存在一个环.
 *   在两种可能的情况中, dfs(w) 都会在 dfs(v) 之前完成, 因此在后序排列中 w 排在 v 之前
 *   而在逆后序中 w 排在 v 之后. 因此任意一条边 v->w 都如我们所愿地从排名较前顶点指向排名较后的顶点
 * - G: 使用 DFS 对有向无环图进行拓扑排序所需的时间和 V+E 成正比
 *   证明: 由代码可知, 第一遍 DFS 保证了不存在有向环, 第二遍 DFS 产生了顶点的逆后续排序. 两次
 *   DFS 都访问了所有的顶点和所有的边, 因此它所需的时间和 V+E 成正比
 *
 * 尽管这个算法简单, 但它被忽略了很多年, 更流行的一种是使用队列存储顶点的更直观的算法 @see e.4.2.30
 */
class Topological
{
    private $order;  // 顶点的拓扑排序

    public function __construct(Digraph $G)
    {
        $cycleFinder = new DirectedCycle($G);
        if (! $cycleFinder->hasCycle()) {
            $dfs = new DepthFirstOrder($G);
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

    /**
     * % php Topological.php ../resource/jobs.txt "/"
     * Calculus
     * Linear Algebra
     * Introduction to CS
     * Advanced Programming
     * Algorithms
     * Theoretical CS
     * Artificial Intelligence
     * Robotics
     * Machine Learning
     * Neural Networks
     * Databases
     * Scientific Computing
     * Computational Biology
     */
    public static function main(array $args): void
    {
        $filename = $args[0];
        $separator = $args[1];
        $sg = new SymbolDigraph($filename, $separator);

        $top = new self($sg->G());
        foreach ($top->order as $v) {
            StdOut::println($sg->name($v));
        }
    }
}