<?php
namespace Algs;

/**
 * p.352
 *
 * 使用 DFS 判断是否是无环图
 */
class Cycle
{
    private $marked;
    private $hasCycle = false;

    public function __construct(Graph $G)
    {
        $this->marked = new Arr('bool', $G->V());
        for ($s = 0; $s < $G->V(); $s++) {
            $this->dfs($G, $s, $s);
        }
    }

    /**
     * 通过 DFS 判定是否是无环图
     *
     * v 是当前 DFS 的顶点, u 是刚被 DFS 的顶点
     */
    private function dfs(Graph $G, int $v, int $u): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w, $v);
            /**
             * 这部分较难理解, 也是算法的核心部分
             *
             *       1 - 2 - 3
             *           |    |
             *           5 -- 4
             *
             * 走到 else, 说明 w 已经被标记过. 那么有两种可能的情况
             * 1. 如果 w == u, 说明 w 是上层 DFS 走过来的 u 点.
             *    如图中 2-3, dfs(3) 时, 是从和 3(v) 相连的点 2(u) 刚走过来, 即将再次处理 2(w)
             *    这种情况不作处理, 因为如果 2(w) 被标记过且等于 2(u), 只要递归返回即可
             * 2. 如果 w != u, 说明 w 不是 u, 但已经在路径上走过.
             *    如从 1-2-3-4-5 过来, dsf(5) 时, 即将处理 5(v) 的相邻顶点 2(w)
             *    2(w) 已被标记 (从 1 走过) 且不等于 4(u), 发现 5 (v) 也能到 2(w), 说明有环
             */
            } else if ($w != $u) {
                $this->hasCycle = true;
            }
        }
    }

    public function hasCycle(): bool
    {
        return $this->hasCycle;
    }

    /**
     * php Cycle.php ../data/tinyG.txt
     * has cycle: 1
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        $cycle = new self($G);
        if (! $cycle->hasCycle()) {
            StdOut::print("NOT ");
        }
        StdOut::println("has cycle");
    }
}