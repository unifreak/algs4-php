<?php
namespace Algs;

/**
 * p.339
 *
 * 深度优先搜索
 *
 * Tremaux 搜索 -- 探索迷宫而不迷路的方法 (迷宫代表图, 通道代表边, 路口代表顶点)
 * 1. 选择一条没有标记过的通道, 你在走过的路上铺一条绳子
 * 2. 标记所有你第一次路过的路口和通道
 * 3. 当来到一个标记过的路口时 (用绳子), 回退到上个路口
 * 4. 当回退到的路口已没有可走得通道时继续回退
 *
 * 深度优先搜索 (DFS):
 * 1. 访问其中一个顶点时, 将它标记为已访问
 * 2. 递归的访问它的所有没有被标记过的邻居顶点
 * DFS 之所以极为简单, 是因为它所基于的概念为人所熟知并且非常容易实现
 *
 * 如果图是连通的, 每个邻接链表中的元素都会被检查到. 要注意:
 * - 算法遍历边和访问顶点的顺序不仅仅是与图的结构或是算法有关, 与图的表示也是是有关的
 * - 每条边都会被访问两次, 且在第二次时总会发现这个顶点已被标记过. 轨迹可能比你想象的长一倍
 *
 * 命题: p.340
 * - A: 深度优先搜索标记与起点连通的所有顶点所需的时间和顶点的度数成正比
 *   证明: 首先, 证明这个算法能够标记与起点 s 连通的所有顶点 (且不会标记其他顶点). 因为算法仅
 *   通过边来寻找顶点, 所以每个被标记过的顶点都与 s 连通. 现在, 假设某个没有被标记过的顶点 w
 *   与 s 连通. 因为 s 本身是被标记过的, 由 s 到 w 的任意一条路径中至少有一条边连接的两个顶点
 *   分别是被标记过的和没有被标记过的, 例如 v-x. 根据算法, 在标记了 v 之后必然会发现 x, 因此
 *   这样的边是不存在的. 前后矛盾. 每个顶点都会被访问一次保证了时间上限 (检查标记的耗时和度数成
 *   正比).
 *
 */
class DepthFirstSearch implements Search
{
    private $marked;
    private $count;

    public function __construct(Graph $G, int $s)
    {
        $this->marked = new Arr('bool', $G->V());
        $this->dfs($G, $s);
    }

    public function dfs(Graph $G, int $v): void
    {
        $this->marked[$v] = true;
        $this->count++;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) $this->dfs($G, $w);
        }
    }

    public function marked(int $w): bool
    {
        return $this->marked[$w];
    }

    public function count(): int
    {
        return $this->count;
    }
}