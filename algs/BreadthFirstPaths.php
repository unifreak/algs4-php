<?php
namespace Algs;

/**
 * p.345
 *
 * 使用广度优先搜索 (BFS) 查找图中从起点 s 到其他所有顶点的最短路径
 * (也可以用 BFS 实现已经用 DFS 实现的 Search API)
 *
 * 命题: p.347
 * - B: 对于从 s 可达的任意顶点 v, BFS 都能找到一条从 s 到 v 的最短路径 (没有其他从 s 到 v
 *   的路径所含的边比这条路径更少)
 *   证明: 由归纳易得队列总是包含零个或多个到起点距离为 k 的顶点, 之后是零个或多个到起点的距离
 *   为 k+1 的顶点, 其中 k 为整数, 起始值为 0. 这意味着顶点是按照它们和 s 的距离的顺序加入或
 *   者离开队列的. 从顶点 v 加入队列到它离开队列之前, 不可能找出到 v 的更短的路径, 而在 v 离开
 *   队列之后发现的所有能够到达 v 的路径都不可能短于 v 在树中的路径长度
 * - B(续): BFS 所需的时间在最坏情况下和 V+E 成正比
 *   证明: 和命题 A 一样 (@see DepthFirstPath), BFS 标记所有与 s 连通的顶点所需的时间也与
 *   它们的度数之和成正比. 如果图是连通的, 这个和就是所有顶点的度数之和, 也就是 2E
 */
class BreadthFirstPaths extends Paths
{
    public function __construct(Graph $G, int $s)
    {
        parent::__construct($G, $s);
        $this->bfs($G, $s);
    }

    /**
     * 标记所有与 s 连通的顶点
     *
     * bfs() 不是递归地, 不像递归中 (@see DepthFirstPaths ) 隐式的使用栈, 它显式地使用了
     * 一个队列. 使用这个队列表保存所有已经被标记过但其邻接表还未被检查过的顶点. 先将起点加入队列,
     * 然后重复以下步骤直到队列为空:
     * 1. 取队列中的下一个顶点 v 并标记它
     * 2. 将与 v 相邻的所有未被标记过的顶点加入队列
     */
    private function bfs(Graph $G, int $s): void
    {
        $queue = new Queue();
        $this->marked[$s] = true;               // 标记起点
        $queue->enqueue($s);                    // 将它加入队列
        while (! $queue->isEmpty()) {
            $v = $queue->dequeue();             // 从队列中删去下一顶点
            foreach ($G->adj($v) as $w) {
                if (! $this->marked[$w]) {      // 对于每个未被标记的相邻顶点
                    $this->edgeTo[$w] = $v;     // 标记它, 因为最短路径已知
                    $this->marked[$w] = true;   // 并加他添加到队列中
                    $queue->enqueue($w);
                }
            }
        }
    }
}