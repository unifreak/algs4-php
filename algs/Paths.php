<?php
namespace Algs;

/**
 * p.342
 *
 * 寻找路径: 计算 s 到与 s 连通的每个顶点之间的路径
 *
 * DFS 和 BFS 不同之处仅在于从数据结构中获取下一个顶点的规则:
 * 1. DFS 不断深入图中并在_栈_中保存了所有分叉的顶点
 *    BFS 则像扇面一般扫描图, 用一个_队列_保存访问过的最前端的顶点
 * 2. DFS 探索一幅图的方式是寻找离起点更远的顶点, 只在碰到死胡同才访问近处的顶点
 *    BFS 首先覆 起点附近的顶点, 都被访问了之后才向前进
 * 3. DFS 的路径通常较长而且曲折
 *    BFS 则短而直接
 */
class Paths
{
    protected $marked; // 这个顶点上调用过 dfs() 了吗?
    protected $edgeTo; // 从起点到一个顶点的已知路径上的最后一个顶点
                       // edgeTo 整形数组起到了 Tremaux 搜索中绳子的作用. 这个数组可以找到
                       // 从每个与 s 连通的顶点回到 s 的路径. 它是一颗由父链接表示的树.
    protected $s;      // 起点

    public function __construct(Graph $G, int $s)
    {
        $this->marked = new Arr('bool', $G->V());
        $this->edgeTo = new Arr('int', $G->V());
        $this->s = $s;
    }

    /**
     * 是否存在从 s 到 v 的路径
     */
    public function hasPathTo(int $v): bool
    {
        return $this->marked[$v];
    }

    /**
     * s 到 v 的路径, 如果不存在则返回 null
     */
    public function pathTo(int $v): ?\Iterator
    {
        if (! $this->hasPathTo($v)) return null;
        // 使用栈可以反序, 从起点到顶点
        $path = new Stack();
        for ($x = $v; $x != $this->s; $x = $this->edgeTo[$x]) {
            $path->push($x);
        }
        $path->push($this->s);
        return $path;
    }

    /**
     * tinyCG:
     *
     *      0 ---------------- 2
     *      | \           /   / |
     *      |  1 -----/     /   |
     *      5 --------- 3 -/---- 4
     *
     * 使用深度优先算法寻找路径:
     * % php DepthFirstPaths.php ../data/tinyCG.txt 0
     * 0 to 0: 0
     * 0 to 1: 0-2-1
     * 0 to 2: 0-2
     * 0 to 3: 0-2-3
     * 0 to 4: 0-2-3-4
     * 0 to 5: 0-2-3-5
     *
     * 使用广度优先算法寻找最短路径:
     * % php BreadthFirstPaths.php ../data/tinyCG.txt 0
     * 0 to 0: 0
     * 0 to 1: 0-1
     * 0 to 2: 0-2
     * 0 to 3: 0-2-3
     * 0 to 4: 0-2-4
     * 0 to 5: 0-5
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        $s = (int) $args[1];
        $search = new static($G, $s);
        for ($v = 0; $v < $G->V(); $v++) {
            StdOut::print("$s to $v: ");
            if ($search->hasPathTo($v)) {
                foreach ($search->pathTo($v) as $x) {
                    if ($x == $search->s) StdOut::print($x);
                    else StdOut::print("-$x");
                }
            }
            StdOut::println();
        }
    }
}