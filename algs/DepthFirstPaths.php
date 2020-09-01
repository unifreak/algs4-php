<?php
namespace Algs;

/**
 * p.343
 *
 * 使用深度优先搜索查找图中的路径
 * (使用 DFS 也可以找出一幅图的所有连通分量 @see CC)
 *
 * 命题: p.344
 * - A(续): 使用深度优先搜索得到从给定起点到任意标记顶点的路径所需的时间与路径的长度成正比
 *   证明: 根据对已经访问过的顶点数量的归纳可得, edgeTo 数组表示了一颗以起点为根节点的树.
 *   pathTo() 方法构造路径所需的时间和路径的长度成正比
 */
class DepthFirstPaths extends Paths
{
    public function __construct(Graph $G, int $s)
    {
        parent::__construct($G, $s);
        $this->dfs($G, $s);
    }

    private function dfs(Graph $G, int $v): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->edgeTo[$w] = $v;
                $this->dfs($G, $w);
            }
        }
    }
}